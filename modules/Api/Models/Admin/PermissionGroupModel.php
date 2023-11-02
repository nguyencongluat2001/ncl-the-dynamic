<?php

namespace Modules\Api\Models\Admin;

use Illuminate\Database\Eloquent\Model;

/**
 * Nhóm quyền
 */
class PermissionGroupModel extends Model
{
    protected $table = 'permission_group';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'code',
        'name',
        'note',
        'order',
        'status',
        'check_cate',
        'permission_action',
        'permission_full',
        'created_at',
        'updated_at'
    ];

    /**
     * @param mixed $query
     * @param mixed $param
     * @param mixed $value
     *
     * @return Query
     */
    private $value;

    public function filter($query, $param, $value)
    {
        switch ($param) {
            case 'code':
                return $query->where("code", $value);
                break;
            case 'id':
                return $query->where("id", $value);
                break;
        }
    }

    public function permissionUsers()
    {
        return $this->hasMany(PermissionUserModel::class, 'permission_group_id', 'id');
    }
}
