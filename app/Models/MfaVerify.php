<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MfaVerify extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'token_id',
        'expire_at',
        'status',
    ];
}
