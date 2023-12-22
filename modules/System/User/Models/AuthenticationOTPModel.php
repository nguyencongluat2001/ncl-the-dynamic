<?php

namespace Modules\System\User\Models;

use Illuminate\Database\Eloquent\Model;

class AuthenticationOTPModel extends Model
{
    protected $table = 'auth_otp';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'phone',
        'otp',
        'created_at',
        'updated_at'
    ];
}
