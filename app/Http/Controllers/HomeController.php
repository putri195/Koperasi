<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Member;
use App\Models\Saving;
use App\Models\CashBook;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login.keycloak');
        }

        // Ambil data member terkait user
        $member = Member::where('user_id', $user->id)->first();

        // Jika member tidak ditemukan, tampilkan pesan bahwa data belum lengkap
        if (!$member) {
            Session::flash('warning', 'Data keanggotaan Anda belum lengkap. Silakan hubungi pengelola atau lengkapi data terlebih dahulu di halaman profil.');
            return view('homepage', [
                'member' => null,
                'user' => $user,
            ]);
        }

        return view('homepage', [
            'member' => $member,
            'user' => $user,
        ]);
    }

    public function strukturOrganisasi()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login.keycloak');
        }

        // Urutan posisi yang ditentukan
        $posisiUrutan = [
            'Ketua',
            'Wakil Ketua',
            'Sekretaris',
            'Bendahara',
            'Pengawas',
            'Manajer',
            'Staf'
        ];

        // Ambil data pegawai (selain anggota) dan urutkan berdasarkan array di atas
        $pegawai = Member::with('user')
            ->where('position', '!=', 'anggota')
            ->get()
            ->sortBy(function ($item) use ($posisiUrutan) {
                return array_search($item->position, $posisiUrutan);
            });

        // Ambil data anggota
        $anggota = Member::with('user')
            ->where('position', 'anggota')
            ->get();

        return view('user.strukturOrganisasi', [
            'pegawai' => $pegawai,
            'anggota' => $anggota,
        ]);
    }

    public function showProfile()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login.keycloak');
        }

        $member = Member::where('user_id', $user->id)->first();

        if (!$member) {
            Session::flash('warning', 'Data keanggotaan Anda belum lengkap. Silakan lengkapi data atau hubungi admin.');
        }

        return view('user.profile', [
            'user' => $user,
            'member' => $member
        ]);
    }

    public function editProfile()
    {
        $user = auth()->user();
        $member = $user->member;

        $fullName = Str::of($user->name)->explode(' ');
        $firstName = $fullName->first(); // kata pertama
        $lastName = $fullName->slice(1)->implode(' '); // sisa kata setelahnya

        return view('user.editProfile', compact('user', 'member', 'firstName', 'lastName'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        // Validasi
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|confirmed|min:6',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'birth_date' => 'required|date',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048'
        ]);

        // Upload foto profil jika ada
        if ($request->hasFile('profile_photo')) {
            $filename = 'profile_' . time() . '.' . $request->profile_photo->extension();
            $path = $request->file('profile_photo')->storeAs('profile_photos', $filename, 'public');
            $user->profile_photo = 'storage/' . $path;
        }

        // Update user
        $user->name = $request->first_name . ' ' . $request->last_name;
        $user->username = $request->username;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        // Update atau buat data member
        $member = $user->member;
        if (!$member) {
            // Kalau belum ada, buat baru
            $member = new Member();
            $member->user_id = $user->id;

            // Ambil member_number terbesar
            $lastMember = Member::whereNotNull('member_number')
                ->orderByDesc('member_number')
                ->first();

            // Default angka jika tidak ada
            $lastNumber = 0;

            if ($lastMember && preg_match('/KOP-\d{4}-\d+/', $lastMember->member_number)) {
                // Ekstrak nomor terakhir setelah dash
                $parts = explode('-', $lastMember->member_number);
                $lastNumber = (int) end($parts);
            }

            // Tahun dari member terbaru
            $year = now()->format('Y');
            $newNumber = $lastNumber + 1;

            $member->member_number = 'KOP-' . $year . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        }

        $member->member_number = $member->member_number;
        $member->gender = $request->gender;
        $member->birth_date = $request->birth_date;
        $member->phone = $request->phone;
        $member->address = $request->address;
        $member->save();

        // 🔐 Update ke Keycloak
        $admintoken = session('keycloak_tokens.admin_token');
        $keycloakId = $user->keycloak_id;

        if ($admintoken && $keycloakId) {
            $keycloakPayload = [
                'username' => $request->username,
                'enabled' => true
            ];

            if ($request->filled('first_name')) {
                $keycloakPayload['firstName'] = $request->first_name;
            }
            if ($request->filled('last_name')) {
                $keycloakPayload['lastName'] = $request->last_name;
            }
            if ($request->filled('email')) {
                $keycloakPayload['email'] = $request->email;
                $keycloakPayload['emailVerified'] = true;
            }
            if ($request->filled('password')) {
                $keycloakPayload['credentials'] = [[
                    'type' => 'password',
                    'value' => $request->password,
                    'temporary' => false
                ]];
            }

            $response = Http::withToken($admintoken)
                ->contentType('application/json')
                ->put(env('KEYCLOAK_BASE_URL') . '/admin/realms/' . env('KEYCLOAK_REALM') . '/users/' . $keycloakId, $keycloakPayload);

            if (!$response->successful()) {
                Log::error('Gagal update user Keycloak: ', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return back()->with('error', 'Gagal memperbarui profil di Keycloak. Coba lagi.');
            }
        }

        return redirect()->route('profil')->with('success', 'Profil berhasil diperbarui.');
    }

    /* ================= TABEL SIMPANAN (LAPORAN) ================= */
    public function laporan(Request $request)
    {
        $tahun = Carbon::now()->year;

        $members = Member::with('user')
            ->orderBy('member_number', 'asc')
            ->get();

        $data = [];
        foreach ($members as $member) {
            // ✅ Saldo per 31 Desember tahun sebelumnya
            $simpananSebelumnya = Saving::where('member_id', $member->id)
                ->whereYear('saving_date', $tahun - 1)
                ->selectRaw("
                    SUM(CASE WHEN type='Pokok' THEN amount ELSE 0 END) as pokok,
                    SUM(CASE WHEN type='Wajib' THEN amount ELSE 0 END) as wajib,
                    SUM(CASE WHEN type='Sukarela' THEN amount ELSE 0 END) as sukarela
                ")->first();

            $totalSebelumnya = ($simpananSebelumnya->pokok ?? 0)
                            + ($simpananSebelumnya->wajib ?? 0)
                            + ($simpananSebelumnya->sukarela ?? 0);

            // ✅ Pembayaran bulanan (Januari - Desember)
            $bulanan = [];
            $totalPembayaran = 0;

            for ($bulan = 1; $bulan <= 12; $bulan++) {
                $pembayaran = Saving::where('member_id', $member->id)
                    ->whereYear('saving_date', $tahun)
                    ->whereMonth('saving_date', $bulan)
                    ->selectRaw("
                        SUM(CASE WHEN type='Pokok' THEN amount ELSE 0 END) as pokok,
                        SUM(CASE WHEN type='Wajib' THEN amount ELSE 0 END) as wajib,
                        SUM(CASE WHEN type='Sukarela' THEN amount ELSE 0 END) as sukarela
                    ")->first();

                $bulanan[$bulan] = [
                    'pokok'    => $pembayaran->pokok ?? 0,
                    'wajib'    => $pembayaran->wajib ?? 0,
                    'sukarela' => $pembayaran->sukarela ?? 0,
                ];

                $totalPembayaran += ($pembayaran->pokok ?? 0) 
                                + ($pembayaran->wajib ?? 0) 
                                + ($pembayaran->sukarela ?? 0);
            }

            // ✅ Pengambilan (selama 1 tahun penuh, total)
            $pengambilan = CashBook::where('reference_table', 'savings')
                ->whereYear('transaction_date', $tahun)
                ->where('credit', '>', 0)
                ->where('description', 'like', "%{$member->user->name}%")
                ->selectRaw("
                    SUM(CASE WHEN transaction_type = 'Simpanan Pokok' THEN credit ELSE 0 END) as pokok,
                    SUM(CASE WHEN transaction_type = 'Simpanan Wajib' THEN credit ELSE 0 END) as wajib,
                    SUM(CASE WHEN transaction_type = 'Simpanan Sukarela' THEN credit ELSE 0 END) as sukarela
                ")->first();

            $totalPengambilan = ($pengambilan->pokok ?? 0)
                            + ($pengambilan->wajib ?? 0)
                            + ($pengambilan->sukarela ?? 0);

            // ✅ Total akhir saldo
            $totalAkhir = $totalSebelumnya + $totalPembayaran - $totalPengambilan;

            $data[] = [
                'member_id'        => $member->id,
                'member_number'    => $member->member_number,
                'name'             => $member->user->name,
                'sebelumnya'       => $simpananSebelumnya,
                'bulanan'          => $bulanan,           // <<<< simpan per bulan
                'pengambilan'      => $pengambilan,
                'total_sebelumnya' => $totalSebelumnya,
                'pembayaran'       => $pembayaran,
                'total_pengambilan'=> $totalPengambilan,
                'total_akhir'      => $totalAkhir,
                'status'           => $member->status, // aktif/tidak aktif
            ];
        }

        return view('user.laporan_simpanan', compact('data','tahun'));
    }



}