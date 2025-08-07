<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanSetting extends Model
{
    protected $table = 'loan_settings';

    protected $fillable = [
        'interest_rate',     // bunga pinjaman (%)
        'due_day',           // tanggal jatuh tempo
        'penalty_amount'     // nominal denda
    ];
}
