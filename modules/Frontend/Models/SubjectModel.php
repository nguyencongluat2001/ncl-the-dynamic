<?php

namespace Modules\Frontend\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Đối tượng thi
 * 
 * @author luatnc
 */
class SubjectModel extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'client';
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'phone',
        'sex',
        'address',
        'date_of_birth',
        'trang_thai',
        'password',
        'rank',
        'last_login_at',
        'created_at',
        'updated_at',
    ];
}
