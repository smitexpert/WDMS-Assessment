<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultiFactorAuth extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'provider',
        'verified_at'
    ];
}
