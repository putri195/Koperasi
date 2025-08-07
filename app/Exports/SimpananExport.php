<?php

namespace App\Exports;

use App\Models\Member;
use App\Models\Saving;
use App\Models\CashBook;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SimpananExport implements FromView
{
    protected $bulan, $tahun;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function view(): View
    {
        $bulan = $this->bulan;
        $tahun = $this->tahun;

        $members = Member::with('user')
            ->orderBy('member_number', 'asc')
            ->get();

        $data = [];
        foreach ($members as $member) {
            $prevMonth = Carbon::createFromDate($tahun, $bulan, 1)->subMonth();

            $simpananSebelumnya = Saving::where('member_id', $member->id)
                ->whereYear('saving_date', $prevMonth->year)
                ->whereMonth('saving_date', $prevMonth->month)
                ->selectRaw("
                    SUM(CASE WHEN type='Pokok' THEN amount ELSE 0 END) as pokok,
                    SUM(CASE WHEN type='Wajib' THEN amount ELSE 0 END) as wajib,
                    SUM(CASE WHEN type='Sukarela' THEN amount ELSE 0 END) as sukarela
                ")->first();

            $pembayaran = Saving::where('member_id', $member->id)
                ->whereYear('saving_date', $tahun)
                ->whereMonth('saving_date', $bulan)
                ->selectRaw("
                    SUM(CASE WHEN type='Pokok' THEN amount ELSE 0 END) as pokok,
                    SUM(CASE WHEN type='Wajib' THEN amount ELSE 0 END) as wajib,
                    SUM(CASE WHEN type='Sukarela' THEN amount ELSE 0 END) as sukarela
                ")->first();

            $pengambilan = CashBook::where('reference_table', 'savings')
                ->whereYear('transaction_date', $tahun)
                ->whereMonth('transaction_date', $bulan)
                ->where('credit', '>', 0)
                ->selectRaw("SUM(credit) as total")
                ->first();

            $total = ($simpananSebelumnya->pokok + $simpananSebelumnya->wajib + $simpananSebelumnya->sukarela)
                   + ($pembayaran->pokok + $pembayaran->wajib + $pembayaran->sukarela)
                   - ($pengambilan->total ?? 0);

            $data[] = [
                'member_number' => $member->member_number,
                'name' => $member->user->name,
                'sebelumnya' => $simpananSebelumnya,
                'pembayaran' => $pembayaran,
                'pengambilan' => $pengambilan,
                'total' => $total,
            ];
        }

        return view('exports.simpanan', compact('data', 'bulan', 'tahun'));
    }
}
