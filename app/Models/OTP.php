<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OTP extends Model
{
    public $fillable = [
        'students_id',
        'otp',
        'expire_at',
        'mobile_number',
    ];

    public $table = 'otps';

    
}
