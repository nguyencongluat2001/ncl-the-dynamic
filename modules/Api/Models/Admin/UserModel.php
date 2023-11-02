<?php

namespace Modules\Api\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\System\Position\Models\PositionModel;
use Modules\System\Users\Models\UnitModel;

/**
 * Người dùng
 */
class UserModel extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'users';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'units_id',
        'position_id',
        'name',
        'address',
        'email',
        'mobile',
        'fax',
        'username',
        'password',
        'order',
        'status',
        'role',
        'sex',
        'birthday',
        'remember_token',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'password',
    ];

    public $owner_code;
    public $unit_type;

    public function position()
    {
        return $this->hasOne(PositionModel::class, 'id', 'position_id');
    }

    public function unit()
    {
        return $this->hasOne(UnitModel::class, 'id', 'units_id');
    }
}
