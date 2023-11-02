<?php

namespace Modules\Api\Models\Admin;

use Illuminate\Database\Eloquent\Model;

/**
 * Luồng quy trình
 */
class WorkFlowModel extends Model
{
    protected $table = 'system_work_flow';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'code',
        'name',
        'unit_group',
        'main_table',
        'note',
        'description',
        'order',
        'status',
        'created_at',
        'updated_at',
    ];
}
