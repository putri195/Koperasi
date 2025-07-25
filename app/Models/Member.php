<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'members';
    
    protected $fillable = [
        'user_id',
        'member_number',
        'gender',
        'birth_date',
        'phone',
        'address',
        'position',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}