<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'currency_id',
        'balance'
    ];

    protected $casts = [
        'balance' => 'float'
    ];


    public function currency() {
        return $this->belongsTo(Currency::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function denominations() {
        return $this->hasMany(Denomination::class);
    }
}
