<?php

namespace Modules\Api\Models\Admin;

use Illuminate\Database\Eloquent\Model;

/**
 * Danh mục đơi tượng của Loại đói 
 */
class ListModel extends Model
{
    protected $table = 'system_list';

    protected $fillable = [
        'id',
        'system_listtype_id',
        'code',
        'name',
        'order',
        'status',
        'owner_code_list',
        'xml_data',
        'created_at',
        'updated_at',
    ];

    public function listtye()
    {
        return $this->belongsTo(ListModel::class, 'id', 'system_listtype_id');
    }

    /**
     * Lấy tên của trạng thái xử lý hồ sơ
     * 
     * @param string $code Mã trạng thái xử lý
     * @return string
     */
    public function getNameStatus($code)
    {
        try {
            return $this->where(
                'system_listtype_id',
                ListTypeModel::where('code', 'TRANG_THAI_XU_LY')->first()->id
            )
                ->where('code', $code)
                ->first()->name;
        } catch (\Exception $e) {
            return '';
        }
    }
}
