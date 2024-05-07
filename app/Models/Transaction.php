<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;


    protected $casts = [
        'user_id'=> 'integer',
        'debit' => 'integer',
        'credit' => 'integer',
        'balance' => 'integer',
        'amount' => 'integer',
        'fee' => 'integer',
        'from_user_id' => 'integer',
        'main_wallet' => 'integer',
        'status' => 'integer',
        'e_charges' => 'integer',
        'charge' => 'integer',
        'resolve' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
