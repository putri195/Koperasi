<?php

namespace App\Exports;

use App\Models\Member;
use App\Models\CashBook;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SimpananWajibExport implements FromView
{
    protected $tahun;

    public function __construct($tahun)
    {
        $this->tahun = $tahun;
    }

    public function view(): View
    {
        $tahun = $this->tahun;

        $members = Member::with('user')->orderBy('member_number')->get();
        $data = [];

        foreach ($members as $member) {
            // Hitung saldo awal sebelum tahun berjalan
            $sisa_awal = CashBook::join('savings', 'cash_book.reference_id', '=', 'savings.custom_id')
                ->where('cash_book.reference_table', 'savings')
                ->where('savings.member_id', $member->id)
                ->where('savings.type', 'Sukarela')
                ->whereYear('cash_book.transaction_date', '<', $tahun)
                ->selectRaw("COALESCE(SUM(cash_book.debit) - SUM(cash_book.credit), 0) as saldo")
                ->value('saldo');

            $row = [
                'member_number' => $member->member_number,
                'name' => $member->user->name,
                'sisa_awal' => $sisa_awal,
                'bulan' => []
            ];

            $sisa = $sisa_awal;

            // Loop Januari–Desember
            for ($bulan = 1; $bulan <= 12; $bulan++) {
                $bayar = CashBook::join('savings', 'cash_book.reference_id', '=', 'savings.custom_id')
                    ->where('cash_book.reference_table', 'savings')
                    ->where('savings.member_id', $member->id)
                    ->where('savings.type', 'Sukarela')
                    ->whereYear('cash_book.transaction_date', $tahun)
                    ->whereMonth('cash_book.transaction_date', $bulan)
                    ->sum('cash_book.debit');

                $ambil = CashBook::join('savings', 'cash_book.reference_id', '=', 'savings.custom_id')
                    ->where('cash_book.reference_table', 'savings')
                    ->where('savings.member_id', $member->id)
                    ->where('savings.type', 'Sukarela')
                    ->whereYear('cash_book.transaction_date', $tahun)
                    ->whereMonth('cash_book.transaction_date', $bulan)
                    ->sum('cash_book.credit');

                $sisa = $sisa + $bayar - $ambil;

                $row['bulan'][$bulan] = [
                    'bayar' => $bayar,
                    'ambil' => $ambil,
                    'sisa'  => $sisa,
                ];
            }

            $data[] = $row;
        }

        // kirim ke blade untuk ditampilkan & diexport
        return view('exports.simpananSukarela', [
            'data' => $data,
            'tahun' => $tahun,
        ]);
    }
}
