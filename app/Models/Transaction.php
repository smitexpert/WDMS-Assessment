<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'wallet_id',
        'type',
        'amount',
    ];

    protected $casts = [
        'amount' => 'float',
    ];

    public function wallet() {
        return $this->belongsTo(Wallet::class);
    }
}
