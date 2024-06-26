<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Denomination extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_id',
        'name',
        'denomination',
        'quantity',
    ];


    public function wallet() {
        return $this->belongsTo(Wallet::class);
    }
}
