<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    use HasFactory;

    protected $table = 'installments';
    protected $fillable = [
        'custom_id',
        'loan_id',
        'amount',
        'installment_date',
        'is_paid',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_id');
    }

    public function cashBook()
    {
        return $this->hasOne(CashBook::class, 'reference_id', 'custom_id')
                    ->where('reference_table', 'installments');
    }

}
