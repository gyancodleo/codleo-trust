<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TwoFactorCode extends Model
{

    protected $fillable = [
        'user_id',
        'user_type',
        'otp',
        'expires_at'
    ];

    protected $dates = ['expires_at'];
}
