<?php

namespace Modules\Api\Models\Admin;

use Illuminate\Database\Eloquent\Model;

/**
 * Module cho từng chức năng (menu cấp 1)
 */
class ModuleModel extends Model
{
    protected $table = 'packet_module';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'code',
        'url',
        'name',
        'icon',
        'order',
        'status',
        'created_at',
        'updated_at',
    ];

    public function action()
    {
        return $this->hasMany(ActionModel::class, 'packet_module_id', 'id');
    }
}
