<?php

namespace Modules\Api\Models\Admin;

use Illuminate\Database\Eloquent\Model;

/**
 * Gateway, các tab chuyển xử lý
 */
class GatewayModel extends Model
{
    protected $table = 'system_wf_gateway';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'system_wf_task_id',
        'packet_event_table_id',
        'permission_group_id',
        'name',
        'next_current_status',
        'next_detail_status',
        'order',
        'default',
        'created_at',
        'updated_at',
    ];
}
