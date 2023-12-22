<?php

namespace Modules\System\User\Models;

use Illuminate\Database\Eloquent\Model;

class UserPassOldModel extends Model
{
    protected $table = 'password_old';
    public $incrementing = false;
    public $timestamps = false;
    
    protected $fillable = [
        'id',
        'user_id',
        'password',
        'created_at',
        'updated_at'
    ];
}