<?php

namespace Modules\Api\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Phân quyền cho người dùng
 */
class PermissionUserModel extends Model
{
    protected $table = 'permission_users';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'permission_group_id',
        'users_id',
        'owner_code',
        'created_at',
        'updated_at',
    ];

    public function permissionGroup(): BelongsTo
    {
        return $this->belongsTo(PermissionGroupModel::class, 'permission_group_id', 'id');
    }
}
