<?php

namespace App\Exports;

use App\Models\Member;
use App\Models\CashBook;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SimpananPokokExport implements FromView
{
    public function view(): View
    {
        $members = Member::with('user')->orderBy('member_number')->get();

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

        return view('exports.simpanan_pokok', compact('data'));
    }
}
