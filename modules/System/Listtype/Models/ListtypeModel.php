<?php

namespace Modules\System\Listtype\Models;

use Illuminate\Database\Eloquent\Model;
use Lang;
use DB;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ListtypeModel extends Model
{
    protected $table = 'system_listtype';

    protected $fillable = [
        'code',
        'name',
        'xml_file_name',
        'order',
        'owner_code_list',
        'status',
        'created_at',
        'updated_at',
    ];

    /**
     * Quan hệ 1-N với list
     */
    public function list(): HasMany
    {
        return $this->hasMany('Modules\System\Listtype\Models\ListModel', 'system_listtype_id');
    }

    /**
     * Lấy tất cả danh mục còn hoạt động
     * 
     * @param int $status
     * @return array
     */
    public function _getAllbyStatus($status = 1)
    {
        return $this->where('status', $status)->select('id', 'name')->get()->toArray();
    }

    public function _getSingle($id, $typeJson = false)
    {
        if ($typeJson) {
            return $this->where('id', $id)->first()->toJson();
        } else {
            return $this->where('id', $id)->first()->toArray();
        }
    }
}
