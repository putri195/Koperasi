<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Member;
use App\Models\Saving;
use App\Models\CashBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\SimpananWajibExport;
use Maatwebsite\Excel\Facades\Excel;

class SimpananController extends Controller
{
    private function checkSessionAndRole($requiredRole = null)
    {
        $tokens = session('keycloak_tokens', []);
        $accessToken = $tokens['access_token'] ?? null;
        $expiresAt = $tokens['expires_in'] ?? null;

        if (!$accessToken || ($expiresAt && now()->timestamp > $expiresAt)) {
            session()->flush();
            return redirect()->route('logout')->with('error', 'Sesi Anda telah habis. Silakan login kembali.');
        }

        if ($requiredRole && auth()->user()->role !== $requiredRole) {
            abort(403, 'Akses ditolak: hanya ' . $requiredRole);
        }

        return $accessToken;
    }

    /* ================= SIMPANAN POKOK ================= */
    public function indexSPokok()
    {
        $accessToken = $this->checkSessionAndRole('admin');
        if ($accessToken instanceof \Illuminate\Http\RedirectResponse) return $accessToken;

        $members = Member::with('user')->get();
        return view('admin.keuangan.form_sPokok', compact('members'));
    }

    public function tambahSimpananPokok(Request $request)
    {
        return $this->storeSimpanan($request, 'Pokok', 'SP');
    }

    /* ================= SIMPANAN WAJIB ================= */
    public function indexSWajib()
    {
        $accessToken = $this->checkSessionAndRole('admin');
        if ($accessToken instanceof \Illuminate\Http\RedirectResponse) return $accessToken;

        $members = Member::with('user')->get();
        return view('admin.keuangan.form_sWajib', compact('members'));
    }

    public function tambahSimpananWajib(Request $request)
    {
        return $this->storeSimpanan($request, 'Wajib', 'SW');
    }

    /* ================= SIMPANAN SUKARELA ================= */
    public function indexSSukarela()
    {
        $accessToken = $this->checkSessionAndRole('admin');
        if ($accessToken instanceof \Illuminate\Http\RedirectResponse) return $accessToken;

        $members = Member::with('user')->get();
        return view('admin.keuangan.form_sSukarela', compact('members'));
    }

    public function tambahSimpananSukarela(Request $request)
    {
        return $this->storeSimpanan($request, 'Sukarela', 'SSK');
    }

    /* ================= FUNGSI GENERIK UNTUK MENYIMPAN ================= */
    private function storeSimpanan(Request $request, $type, $prefix)
    {
        $accessToken = $this->checkSessionAndRole('admin');
        if ($accessToken instanceof \Illuminate\Http\RedirectResponse) return $accessToken;

        $request->validate([
            'tanggal'       => 'required|date',
            'keterangan'    => 'nullable|string',
            'JumlahSimpanan'=> 'required|numeric|min:0',
            'pilih_anggota' => 'required|integer',
        ]);

        DB::beginTransaction();
        try {
            // Ambil custom_id terakhir
            $lastCustomId = Saving::where('type', $type)
                ->orderByDesc('id')
                ->value('custom_id');

            if ($lastCustomId) {
                $lastNumber = (int) str_replace($prefix . '-', '', $lastCustomId);
                $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $newNumber = "0001";
            }
            $customId = $prefix . "-" . $newNumber;

            // Simpan ke tabel savings
            $saving = Saving::create([
                'custom_id'   => $customId,
                'member_id'   => $request->pilih_anggota,
                'type'        => $type,
                'amount'      => $request->JumlahSimpanan,
                'saving_date' => $request->tanggal,
            ]);

            // Hitung saldo terakhir
            $lastBalance = CashBook::orderByDesc('id')->value('balance') ?? 0;

            $debit = $request->JumlahSimpanan; // otomatis masuk debit
            $credit = 0;
            $newBalance = $lastBalance + $debit;

            // Simpan ke cash_book
            CashBook::create([
                'transaction_date' => $request->tanggal,
                'description'      => $request->keterangan ?? "Simpanan {$type}",
                'transaction_type' => "Simpanan {$type}",
                'reference_table'  => 'savings',
                'reference_id'     => $customId,
                'debit'            => $debit,
                'credit'           => $credit,
                'balance'          => $newBalance,
            ]);

            DB::commit();

            return redirect()->back()->with('success', "Simpanan {$type} berhasil disimpan!");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    /* ================= TABEL SIMPANAN (LAPORAN) ================= */
    public function laporan(Request $request)
    {
        $accessToken = $this->checkSessionAndRole('admin');
        if ($accessToken instanceof \Illuminate\Http\RedirectResponse) return $accessToken;
        
        // Default: bulan & tahun sekarang
        $bulan = Carbon::now()->month;
        $tahun = Carbon::now()->year;

        // Kalau ada request periode, override
        if ($request->filled('periode')) {
            [$bln, $thn] = explode('-', $request->periode);
            $bulan = (int) $bln;
            $tahun = (int) $thn;
        }

        $search = $request->search;

        $members = Member::with('user')
            ->when($search, function ($q) use ($search) {
                $q->where('member_number', 'like', "%$search%")
                ->orWhereHas('user', fn($qu) => $qu->where('name', 'like', "%$search%"));
            })
            ->orderBy('member_number', 'asc')
            ->get();

        $data = [];
        foreach ($members as $member) {
            // 🔥 Tentukan tanggal cutoff (awal bulan yang difilter)
            $cutoffDate = Carbon::create($tahun, $bulan, 1);

            // ✅ Hitung simpanan sebelumnya (semua transaksi sebelum periode)
            $saldoAwal = Saving::where('member_id', $member->id)
                ->where('saving_date', '<', $cutoffDate)
                ->selectRaw("
                    SUM(CASE WHEN type='Pokok' THEN amount ELSE 0 END) as pokok,
                    SUM(CASE WHEN type='Wajib' THEN amount ELSE 0 END) as wajib,
                    SUM(CASE WHEN type='Sukarela' THEN amount ELSE 0 END) as sukarela
                ")->first();

            $ambilAwal = CashBook::where('reference_table', 'savings')
                ->where('transaction_date', '<', $cutoffDate)
                ->where('credit', '>', 0)
                ->selectRaw("
                    SUM(CASE WHEN transaction_type='Simpanan Pokok' THEN credit ELSE 0 END) as pokok,
                    SUM(CASE WHEN transaction_type='Simpanan Wajib' THEN credit ELSE 0 END) as wajib,
                    SUM(CASE WHEN transaction_type='Simpanan Sukarela' THEN credit ELSE 0 END) as sukarela
                ")->first();

            $simpananSebelumnya = (object) [
                'pokok'    => ($saldoAwal->pokok ?? 0) - ($ambilAwal->pokok ?? 0),
                'wajib'    => ($saldoAwal->wajib ?? 0) - ($ambilAwal->wajib ?? 0),
                'sukarela' => ($saldoAwal->sukarela ?? 0) - ($ambilAwal->sukarela ?? 0),
            ];

            // ✅ Pembayaran bulan terpilih
            $pembayaran = Saving::where('member_id', $member->id)
                ->whereYear('saving_date', $tahun)
                ->whereMonth('saving_date', $bulan)
                ->selectRaw("
                    SUM(CASE WHEN type='Pokok' THEN amount ELSE 0 END) as pokok,
                    SUM(CASE WHEN type='Wajib' THEN amount ELSE 0 END) as wajib,
                    SUM(CASE WHEN type='Sukarela' THEN amount ELSE 0 END) as sukarela
                ")->first();

            // ✅ Pengambilan bulan terpilih
            $pengambilan = CashBook::where('reference_table', 'savings')
                ->whereYear('transaction_date', $tahun)
                ->whereMonth('transaction_date', $bulan)
                ->where('credit', '>', 0)
                ->selectRaw("
                    SUM(CASE WHEN transaction_type = 'Simpanan Pokok' THEN credit ELSE 0 END) as pokok,
                    SUM(CASE WHEN transaction_type = 'Simpanan Wajib' THEN credit ELSE 0 END) as wajib,
                    SUM(CASE WHEN transaction_type = 'Simpanan Sukarela' THEN credit ELSE 0 END) as sukarela
                ")->first();

            // ✅ Total akhir
            $total = 
                (($simpananSebelumnya->pokok ?? 0) + ($simpananSebelumnya->wajib ?? 0) + ($simpananSebelumnya->sukarela ?? 0))
            + (($pembayaran->pokok ?? 0) + ($pembayaran->wajib ?? 0) + ($pembayaran->sukarela ?? 0))
            - (($pengambilan->pokok ?? 0) + ($pengambilan->wajib ?? 0) + ($pengambilan->sukarela ?? 0));

            $data[] = [
                'member_id'     => $member->id,
                'member_number' => $member->member_number,
                'name'          => $member->user->name,
                'sebelumnya'    => $simpananSebelumnya,
                'pembayaran'    => $pembayaran,
                'pengambilan'   => $pengambilan,
                'total'         => $total,
            ];
        }

        // Summary total keseluruhan
        $summary = Saving::selectRaw("
            SUM(CASE WHEN type='Pokok' THEN amount ELSE 0 END) as total_pokok,
            SUM(CASE WHEN type='Wajib' THEN amount ELSE 0 END) as total_wajib,
            SUM(CASE WHEN type='Sukarela' THEN amount ELSE 0 END) as total_sukarela
        ")->first();

        // Daftar periode yg tersedia di DB
        $availablePeriods = Saving::selectRaw("YEAR(saving_date) as tahun, MONTH(saving_date) as bulan")
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        return view('admin.simpanan.tabelSimpanan', compact('data', 'summary', 'bulan', 'tahun', 'availablePeriods'));    
    }


    /* ================= EXPORT EXCEL ================= */
    public function export(Request $request)
    {
        $accessToken = $this->checkSessionAndRole('admin');
        if ($accessToken instanceof \Illuminate\Http\RedirectResponse) return $accessToken;
        
        $bulan = $request->bulan ?? Carbon::now()->month;
        $tahun = $request->tahun ?? Carbon::now()->year;

        return Excel::download(new \App\Exports\SimpananExport($bulan, $tahun), "Simpanan-$bulan-$tahun.xlsx");
    }

    // Form Edit
    public function edit($id, $tahun, $bulan)
    {
        $accessToken = $this->checkSessionAndRole('admin');
        if ($accessToken instanceof \Illuminate\Http\RedirectResponse) return $accessToken;
        
        $member = Member::with('user')->findOrFail($id);

        // Ambil semua pembayaran simpanan bulan itu
        $pembayaran = Saving::where('member_id', $id)
            ->whereYear('saving_date', $tahun)
            ->whereMonth('saving_date', $bulan)
            ->get()
            ->groupBy('type');

        // Ambil semua pengambilan bulan itu
        $pengambilan = CashBook::where('reference_table', 'savings')
            ->whereYear('transaction_date', $tahun)
            ->whereMonth('transaction_date', $bulan)
            ->where('credit', '>', 0)
            ->get()
            ->groupBy('transaction_type');

        return view('admin.simpanan.editSimpanan', compact('member', 'tahun', 'bulan', 'pembayaran', 'pengambilan'));
    }


    // Update simpanan
    public function update(Request $request, $memberId, $tahun, $bulan)
    {
        $accessToken = $this->checkSessionAndRole('admin');
        if ($accessToken instanceof \Illuminate\Http\RedirectResponse) return $accessToken;

        $request->validate([
            's_pokok' => 'nullable|numeric',
            's_wajib' => 'nullable|numeric',
            's_sukarela' => 'nullable|numeric',
            's_pokok1' => 'nullable|numeric',
            's_wajib1' => 'nullable|numeric',
            's_sukarela1' => 'nullable|numeric',
        ]);

        $member = Member::with('user')->findOrFail($memberId);
        $tanggal = \Carbon\Carbon::createFromDate($tahun, $bulan, 1);

        // Helper untuk update/insert saving + cashbook
        $this->updateSimpanan($member, 'Pokok', $request->s_pokok, $request->s_pokok1, $tanggal);
        $this->updateSimpanan($member, 'Wajib', $request->s_wajib, $request->s_wajib1, $tanggal);
        $this->updateSimpanan($member, 'Sukarela', $request->s_sukarela, $request->s_sukarela1, $tanggal);

        return redirect()->route('simpanan.laporan', ['tahun' => $tahun, 'bulan' => $bulan])
            ->with('success', 'Simpanan berhasil diperbarui');
    }

    private function updateSimpanan($member, $type, $bayar, $ambil, $tanggal)
    {
        if (($bayar ?? 0) > 0 || ($ambil ?? 0) > 0) {
            // Simpanan
            $saving = Savings::updateOrCreate(
                [
                    'member_id' => $member->id,
                    'type' => $type,
                    'saving_date' => $tanggal,
                ],
                [
                    'amount' => ($bayar ?? 0) - ($ambil ?? 0),
                ]
            );

            // CashBook
            CashBook::updateOrCreate(
                [
                    'reference_id' => $saving->custom_id,
                    'reference_table' => 'savings',
                    'transaction_date' => $tanggal,
                ],
                [
                    'transaction_type' => "Simpanan $type",
                    'debit' => $bayar ?? 0,
                    'credit' => $ambil ?? 0,
                    'description' => "Update simpanan $type anggota {$member->user->name}",
                ]
            );
        }
    }


    /* ================= TABEL SIMPANAN POKOK ================= */
    public function tabelSPokok(Request $request)
    {
        $accessToken = $this->checkSessionAndRole('admin');
        if ($accessToken instanceof \Illuminate\Http\RedirectResponse) return $accessToken;
        
        $search = $request->search;

        $members = Member::with('user')
            ->when($search, function ($q) use ($search) {
                $q->where('member_number', 'like', "%$search%")
                  ->orWhereHas('user', fn($qu) => $qu->where('name', 'like', "%$search%"));
            })
            ->orderBy('member_number', 'asc')
            ->get();

        $data = [];
        foreach ($members as $member) {
            // Total pembayaran (debet)
            $bayar = CashBook::join('savings', 'cash_book.reference_id', '=', 'savings.custom_id')
                ->where('cash_book.reference_table', 'savings')
                ->where('savings.member_id', $member->id)
                ->where('savings.type', 'Pokok')
                ->sum('cash_book.debit');

            // Total pengambilan (credit)
            $ambil = CashBook::join('savings', 'cash_book.reference_id', '=', 'savings.custom_id')
                ->where('cash_book.reference_table', 'savings')
                ->where('savings.member_id', $member->id)
                ->where('savings.type', 'Pokok')
                ->sum('cash_book.credit');

            // Sisa simpanan pokok
            $sisa = $bayar - $ambil;

            $data[] = [
                'member_number' => $member->member_number,
                'name' => $member->user->name,
                'bayar' => $bayar,
                'ambil' => $ambil,
                'sisa' => $sisa,
            ];
        }

        return view('admin.simpanan.tabelSPokok', compact('data'));
    }

    public function exportSpokok()
    {
        $accessToken = $this->checkSessionAndRole('admin');
        if ($accessToken instanceof \Illuminate\Http\RedirectResponse) return $accessToken;
        
        return Excel::download(new \App\Exports\SimpananPokokExport, 'simpanan_pokok.xlsx');
    }

    /* ================= TABEL SIMPANAN WAJIB ================= */
    public function tabelSWajib(Request $request)
    {
        $accessToken = $this->checkSessionAndRole('admin');
        if ($accessToken instanceof \Illuminate\Http\RedirectResponse) return $accessToken;
        
        $tahun   = $request->tahun ?? now()->year;
        $quarter = (int)($request->quarter ?? 1); // default kuartal 1 (Jan–Apr)
        $search  = $request->search;

        // Ambil semua tahun yang tersedia dari database
        $availableYears = CashBook::where('transaction_type', 'Simpanan Wajib')
            ->selectRaw("DISTINCT YEAR(transaction_date) as tahun")
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // Tentukan range bulan sesuai kuartal
        $quarterBulan = match ($quarter) {
            1 => [1, 2, 3, 4],       // Jan - Apr
            2 => [5, 6, 7, 8],       // Mei - Agu
            3 => [9, 10, 11, 12],    // Sep - Des
        };

        // Ambil cutoff date (bulan pertama di kuartal ini)
        $cutoff = \Carbon\Carbon::create($tahun, min($quarterBulan), 1)->startOfMonth();

        $members = Member::with('user')
            ->when($search, function ($q) use ($search) {
                $q->where('member_number', 'like', "%$search%")
                ->orWhereHas('user', fn($qu) => $qu->where('name', 'like', "%$search%"));
            })
            ->orderBy('member_number', 'asc')
            ->get();

        $data = [];
        foreach ($members as $member) {
            // ===== Hitung Sisa Awal =====
            if (in_array(1, $quarterBulan)) {
                // Kalau kuartal ada Januari → semua transaksi sampai Desember tahun sebelumnya
                $cutoffAkhir = Carbon::create($tahun - 1, 12, 31)->endOfDay();

                $sisa_awal = CashBook::join('savings', 'cash_book.reference_id', '=', 'savings.custom_id')
                    ->where('cash_book.reference_table', 'savings')
                    ->where('savings.member_id', $member->id)
                    ->where('savings.type', 'Wajib')
                    ->whereDate('cash_book.transaction_date', '<=', $cutoffAkhir)
                    ->selectRaw("COALESCE(SUM(cash_book.debit) - SUM(cash_book.credit), 0) as saldo")
                    ->value('saldo');
            } else {
                // Kalau bukan Januari → ambil transaksi sampai sebelum kuartal ini
                $sisa_awal = CashBook::join('savings', 'cash_book.reference_id', '=', 'savings.custom_id')
                    ->where('cash_book.reference_table', 'savings')
                    ->where('savings.member_id', $member->id)
                    ->where('savings.type', 'Wajib')
                    ->whereDate('cash_book.transaction_date', '<', $cutoff)
                    ->selectRaw("COALESCE(SUM(cash_book.debit) - SUM(cash_book.credit), 0) as saldo")
                    ->value('saldo');
            }

            $row = [
                'member_number' => $member->member_number,
                'name' => $member->user->name,
                'sisa_awal' => $sisa_awal,
                'sisa' => $sisa_awal,
                'bulan' => []
            ];

            // ===== Loop tiap bulan di kuartal =====
            foreach ($quarterBulan as $bulan) {
                $awalBulan = Carbon::create($tahun, $bulan, 1)->startOfMonth();
                $akhirBulan = Carbon::create($tahun, $bulan, 1)->endOfMonth();

                $bayar = CashBook::join('savings', 'cash_book.reference_id', '=', 'savings.custom_id')
                    ->where('cash_book.reference_table', 'savings')
                    ->where('savings.member_id', $member->id)
                    ->where('savings.type', 'Wajib')
                    ->whereBetween('cash_book.transaction_date', [$awalBulan, $akhirBulan])
                    ->sum('cash_book.debit');

                $ambil = CashBook::join('savings', 'cash_book.reference_id', '=', 'savings.custom_id')
                    ->where('cash_book.reference_table', 'savings')
                    ->where('savings.member_id', $member->id)
                    ->where('savings.type', 'Wajib')
                    ->whereBetween('cash_book.transaction_date', [$awalBulan, $akhirBulan])
                    ->sum('cash_book.credit');

                // saldo bulan ini = saldo sebelumnya + bayar - ambil
                $row['sisa'] = $row['sisa'] + $bayar - $ambil;

                $row['bulan'][$bulan] = [
                    'bayar' => $bayar,
                    'ambil' => $ambil,
                    'sisa'  => $row['sisa'],
                ];
            }

            $data[] = $row;
        }

        return view('admin.simpanan.tabelSWajib', compact(
            'data', 'tahun', 'availableYears', 'search', 'quarter', 'quarterBulan'
        ));
    }


    public function exportSwajib(Request $request)
    {
        $accessToken = $this->checkSessionAndRole('admin');
        if ($accessToken instanceof \Illuminate\Http\RedirectResponse) return $accessToken;
        
        $tahun = $request->tahun ?? now()->year;
        return Excel::download(new SimpananWajibExport($tahun), "simpanan_wajib_$tahun.xlsx");
    }

    /* ================= TABEL SIMPANAN SUKARELA ================= */
    public function tabelSSukarela(Request $request)
    {
        $accessToken = $this->checkSessionAndRole('admin');
        if ($accessToken instanceof \Illuminate\Http\RedirectResponse) return $accessToken;
        
        $tahun   = $request->tahun ?? now()->year;
        $quarter = (int)($request->quarter ?? 1); // default kuartal 1 (Jan–Apr)
        $search  = $request->search;

        // Ambil semua tahun yang tersedia dari database
        $availableYears = CashBook::where('transaction_type', 'Simpanan Sukarela')
            ->selectRaw("DISTINCT YEAR(transaction_date) as tahun")
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // Tentukan range bulan sesuai kuartal
        $quarterBulan = match ($quarter) {
            1 => [1, 2, 3, 4],       // Jan - Apr
            2 => [5, 6, 7, 8],       // Mei - Agu
            3 => [9, 10, 11, 12],    // Sep - Des
        };

        // Ambil cutoff date (bulan pertama di kuartal ini)
        $cutoff = \Carbon\Carbon::create($tahun, min($quarterBulan), 1)->startOfMonth();

        $members = Member::with('user')
            ->when($search, function ($q) use ($search) {
                $q->where('member_number', 'like', "%$search%")
                ->orWhereHas('user', fn($qu) => $qu->where('name', 'like', "%$search%"));
            })
            ->orderBy('member_number', 'asc')
            ->get();

        $data = [];
        foreach ($members as $member) {
            // ===== Hitung Sisa Awal =====
            if (in_array(1, $quarterBulan)) {
                // Kalau kuartal ada Januari → ambil semua transaksi sampai Desember tahun sebelumnya
                $cutoffAkhir = Carbon::create($tahun - 1, 12, 31)->endOfDay();

                $sisa_awal = CashBook::join('savings', 'cash_book.reference_id', '=', 'savings.custom_id')
                    ->where('cash_book.reference_table', 'savings')
                    ->where('savings.member_id', $member->id)
                    ->where('savings.type', 'Sukarela')
                    ->whereDate('cash_book.transaction_date', '<=', $cutoffAkhir)
                    ->selectRaw("COALESCE(SUM(cash_book.debit) - SUM(cash_book.credit), 0) as saldo")
                    ->value('saldo');
            } else {
                // Kalau bukan Januari → ambil transaksi sampai sebelum cutoff
                $sisa_awal = CashBook::join('savings', 'cash_book.reference_id', '=', 'savings.custom_id')
                    ->where('cash_book.reference_table', 'savings')
                    ->where('savings.member_id', $member->id)
                    ->where('savings.type', 'Sukarela')
                    ->whereDate('cash_book.transaction_date', '<', $cutoff)
                    ->selectRaw("COALESCE(SUM(cash_book.debit) - SUM(cash_book.credit), 0) as saldo")
                    ->value('saldo');
            }

            $row = [
                'member_number' => $member->member_number,
                'name' => $member->user->name,
                'sisa_awal' => $sisa_awal,
                'sisa' => $sisa_awal,
                'bulan' => []
            ];

            // ===== Loop tiap bulan di kuartal =====
            foreach ($quarterBulan as $bulan) {
                $awalBulan = Carbon::create($tahun, $bulan, 1)->startOfMonth();
                $akhirBulan = Carbon::create($tahun, $bulan, 1)->endOfMonth();

                $bayar = CashBook::join('savings', 'cash_book.reference_id', '=', 'savings.custom_id')
                    ->where('cash_book.reference_table', 'savings')
                    ->where('savings.member_id', $member->id)
                    ->where('savings.type', 'Sukarela')
                    ->whereBetween('cash_book.transaction_date', [$awalBulan, $akhirBulan])
                    ->sum('cash_book.debit');

                $ambil = CashBook::join('savings', 'cash_book.reference_id', '=', 'savings.custom_id')
                    ->where('cash_book.reference_table', 'savings')
                    ->where('savings.member_id', $member->id)
                    ->where('savings.type', 'Sukarela')
                    ->whereBetween('cash_book.transaction_date', [$awalBulan, $akhirBulan])
                    ->sum('cash_book.credit');

                // saldo bulan ini = saldo sebelumnya + bayar - ambil
                $row['sisa'] = $row['sisa'] + $bayar - $ambil;

                $row['bulan'][$bulan] = [
                    'bayar' => $bayar,
                    'ambil' => $ambil,
                    'sisa'  => $row['sisa'],
                ];
            }

            $data[] = $row;
        }

        return view('admin.simpanan.tabelSSukarela', compact(
            'data', 'tahun', 'availableYears', 'search', 'quarter', 'quarterBulan'
        ));
    }


    public function exportSsukarela(Request $request)
    {
        $accessToken = $this->checkSessionAndRole('admin');
        if ($accessToken instanceof \Illuminate\Http\RedirectResponse) return $accessToken;
        
        $tahun = $request->tahun ?? now()->year;
        return Excel::download(new SimpananSukarelaExport($tahun), "simpanan_wajib_$tahun.xlsx");
    }
}
