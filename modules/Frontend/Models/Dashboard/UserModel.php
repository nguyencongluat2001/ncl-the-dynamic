<?php

namespace Modules\Frontend\Models\Dashboard;

use Illuminate\Database\Eloquent\Model;
use Modules\Frontend\Models\Dashboard\UserInfoModel;

class UserModel extends Model
{
    protected $table = 'users';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'address',
        'phone',
        'email',
        'avatar',
        'password',
        'dateBirth',
        'role',
        'status',
        'id_personnel',
        'account_type_vip',
        'date_update_vip',
        'created_at',
        'updated_at'
    ];

    public function filter($query, $param, $value)
    {
        switch ($param) {
            case 'search':
                $this->value = $value;
                return $query->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->value . '%')
                          ->orWhere('id_personnel', 'like', '%' . $this->value . '%')
                          ->orWhere('phone', 'like', '%' . $this->value . '%')
                          ->orWhere('email', 'like', '%' . $this->value . '%');
                });       
                return $query;
            case 'role':
                $query->whereIn('role', $value);
                return $query;
            default:
                return $query;
        }
    }
    public function userInfo()
    {
        return $this->belongsTo(UserInfoModel::class, 'id', 'user_id');
    }
}
