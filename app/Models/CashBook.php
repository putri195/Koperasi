<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashBook extends Model
{
    use HasFactory;

    protected $table = 'cash_book';

    protected $fillable = [
        'transaction_date',
        'description',
        'transaction_type',
        'reference_table',
        'reference_id',
        'debit',
        'credit',
        'balance',
    ];

    // Relasi ke transaksi saving
    public function relatedSaving()
    {
        return $this->belongsTo(Saving::class, 'reference_id', 'custom_id')
                    ->where('reference_table', 'savings');
    }

    // Relasi ke transaksi loan
    public function relatedLoan()
    {
        return $this->belongsTo(Loan::class, 'reference_id', 'custom_id')
                    ->where('reference_table', 'loans');
    }

    // Relasi ke transaksi installment
    public function relatedInstallment()
    {
        return $this->belongsTo(Installment::class, 'reference_id', 'custom_id')
                    ->where('reference_table', 'installments');
    }

    // Relasi ke transaksi bunga (loan interests)
    public function relatedInterest()
    {
        return $this->belongsTo(LoanInterest::class, 'reference_id', 'custom_id')
                    ->where('reference_table', 'loan_interests');
    }
}
