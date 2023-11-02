<?php

namespace Modules\Api\Models\Admin;

use Illuminate\Database\Eloquent\Model;

/**
 * Cấu hình từng tab chuyển xử lý
 */
class TaskModel extends Model
{
    protected $table = 'system_wf_task';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'system_work_flow_id',
        'packet_event_table_id',
        'name',
        'work_type',
        'description',
        'process_type',
        'process_type_staff',
        'process_type_department',
        'process_type_unit',
        'created_at',
        'updated_at',
    ];
}
