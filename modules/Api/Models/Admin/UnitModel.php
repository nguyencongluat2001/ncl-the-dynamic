<?php

namespace Modules\Api\Models\Admin;

use Illuminate\Database\Eloquent\Model;

/**
 * Đơn vị
 */
class UnitModel extends Model
{
    protected $table = 'units';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'units_id',
        'code',
        'name',
        'address',
        'mobile',
        'email',
        'fax',
        'order',
        'status',
        'owner_code',
        'owner_ward',
        'type_group',
        'created_at',
        'updated_at',
    ];

    /**
     * Lấy tên đơn vị cha
     */
    public function parent()
    {
        return $this->belongsTo(UnitModel::class, 'units_id', 'id');
    }
}
