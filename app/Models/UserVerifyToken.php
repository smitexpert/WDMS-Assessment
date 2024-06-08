<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVerifyToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'provider',
        'code',
        'expire_at',
        'status',
    ];
}
