<?php

namespace Modules\System\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    public function parent(): BelongsTo
    {
        return $this->belongsTo(UnitModel::class, 'units_id', 'id');
    }

    /**
     * Lấy tên đơn vị con
     */
    public function childrens(): HasMany
    {
        return $this->hasMany(UnitModel::class, 'units_id', 'id');
    }

    /**
     * Lấy user trong đơn vị
     */
    public function users(): HasMany
    {
        return $this->hasMany(UserModel::class, 'units_id', 'id');
    }
}
