<?php

namespace Modules\Api\Models\Admin;

use Illuminate\Database\Eloquent\Model;

/**
 * Loại danh mục
 */
class ListTypeModel extends Model
{
    protected $table = 'system_listtype';

    protected $fillable = [
        'id',
        'code',
        'name',
        'xml_file_name',
        'order',
        'owner_code_list',
        'status',
        'created_at',
        'updated_at',
    ];

    public function filter($query, $param, $value)
    {
        switch ($param) {
            case 'system_listtype_id':
                return $query->where("system_listtype_id", $value);
            case 'code':
                return $query->where("code", $value);
            default:
                return $query;
        }
    }
    public function ListModel()
    {
        return $this->hasMany(ListModel::class, 'system_listtype_id', 'id');
    }
}
