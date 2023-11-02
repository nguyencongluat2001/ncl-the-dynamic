<?php

namespace Modules\Api\Models\Admin;

use Illuminate\Database\Eloquent\Model;

/**
 * Button trên từng màn hình Action
 */
class EventTableModel extends Model
{
    protected $table = 'packet_event_table';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'packet_action_id',
        'code',
        'icon',
        'name',
        'color',
        'url',
        'type',
        'print_form',
        'form_title',
        'form_button_config',
        'form_config',
        'client_form_config',
        'order',
        'status',
        'created_at',
        'updated_at',
        'table_name',
    ];
}
