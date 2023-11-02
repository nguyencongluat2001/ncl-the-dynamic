<?php

namespace Modules\Api\Models\Admin;

use Illuminate\Database\Eloquent\Model;

/**
 * Nhóm chức vụ
 */
class PositionGroupModel extends Model
{
    protected $table = 'position_group';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'code',
        'name',
        'order',
        'status',
        'created_at',
        'updated_at',
    ];

    public function position()
    {
        return $this->hasMany(PositionModel::class, 'position_group_id', 'id');
    }
}
