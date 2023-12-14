<?php

namespace Modules\Frontend\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Bảng bài thi
 * 
 * @author luatnc
 */
class ExamModel extends Model
{
    protected $table = 'bai_thi';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'dot_thi_id',
        'doi_tuong_id',
        'doi_tuong_ho_ten',
        'doi_tuong_email',
        'doi_tuong_don_vi',
        'thoi_diem_nop_bai',
        'thoi_gian_lam_bai',
        'so_dap_an_dung',
        'diem',
        'du_doan_so_nguoi',
        'created_at',
        'updated_at',
    ];

    public function filter($query, $param, $value)
    {
        switch ($param) {
            case 'search':
                return $query->where(function ($query) use ($value) {
                    $query->where('doi_tuong_ho_ten', 'like', '%' . $value . '%')
                        ->where('doi_tuong_email', 'like', '%' . $value . '%')
                        ->where('du_doan_so_nguoi', 'like', '%' . $value . '%');
                });
                return $query;
            case 'doi_tuong_id':
                $query->where('doi_tuong_id', $value);
                return $query;
            case 'nam':
                $query->whereRelation('contest', 'nam', $value);
                return $query;
            case 'type_order':
                $query->orderBy('created_at', 'ASC');
                return $query;
            default:
                return $query;
        }
    }

    /**
     * Relationship với bảng đợt thi
     */
    public function contest()
    {
        return $this->hasOne(ContestModel::class, 'id', 'dot_thi_id');
    }
}
