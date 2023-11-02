<?php

namespace Modules\Api\Models\Admin;

use Illuminate\Database\Eloquent\Model;

/**
 * Chức vụ
 */
class PositionModel extends Model
{
    protected $table = 'position';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'position_group_id',
        'code',
        'name',
        'order',
        'status',
        'created_at',
        'updated_at',
    ];
}
