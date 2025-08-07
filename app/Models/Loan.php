<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $table = 'loans';
    protected $fillable = [
        'custom_id',
        'member_id',
        'amount',
        'loan_date',
        'duration_months',
        'interest_rate',
        'status',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function installments()
    {
        return $this->hasMany(Installment::class, 'loan_id');
    }

    public function interests()
    {
        return $this->hasMany(LoanInterest::class, 'loan_id');
    }

    public function cashBook()
    {
        return $this->hasOne(CashBook::class, 'reference_id', 'custom_id')
                    ->where('reference_table', 'loans');
    }

}
