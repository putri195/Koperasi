<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saving extends Model
{
    use HasFactory;

    protected $table = 'savings';
    protected $fillable = [
        'custom_id',
        'member_id',
        'type',
        'amount',
        'saving_date',
    ];

    // Relasi ke member
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function cashBook()
    {
        return $this->hasOne(CashBook::class, 'reference_id', 'custom_id')
                    ->where('reference_table', 'savings');
    }

}
