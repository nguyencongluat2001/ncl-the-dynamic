<?php

namespace Modules\Frontend\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Đối tượng thi
 * 
 * @author khunogtq
 */
class SubjectModel extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'doi_tuong';
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'ho_ten',
        'cmnd',
        'email',
        'don_vi',
        'cap_don_vi',
        'trang_thai',
        'password',
        'last_login_at',
        'created_at',
        'updated_at',
    ];
}
