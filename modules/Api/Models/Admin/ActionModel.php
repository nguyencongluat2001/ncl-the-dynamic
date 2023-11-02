<?php

namespace Modules\Api\Models\Admin;

use Illuminate\Database\Eloquent\Model;

/**
 * Action của Module (menu cấp 2)
 */
class ActionModel extends Model
{
    protected $table = 'packet_action';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'packet_module_id',
        'code',
        'icon',
        'name',
        'url',
        'title',
        'sql_config',
        'view_index_config',
        'search_config',
        'order',
        'status',
        'created_at',
        'updated_at',
    ];

    public function etable()
    {
        return $this->hasMany(EventTableModel::class, 'packet_action_id', 'id');
    }
}
