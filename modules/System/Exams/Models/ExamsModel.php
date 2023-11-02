<?php

namespace Modules\System\Exams\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;
use Modules\System\Examinations\Models\ExaminationsModel;
use Modules\System\Objects\Models\ObjectsModel;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamsModel extends Model
{

    protected $table = 'bai_thi';
    public $incrementing = false;
    public $timestamps = false;
    // public $sortable = ['order'];

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
        'updated_at'
    ];

    public function filter($query, $param, $value)
    {
        switch ($param) {
            case 'search':
                $this->value = $value;
                return $query->where(function ($query) {
                    $query->where('doi_tuong_ho_ten', 'like', '%' . $this->value . '%')
                          ->where('doi_tuong_email', 'like', '%' . $this->value . '%');
                });       
                return $query;
            case 'role':
                $query->where('role', $value);
                return $query;
            case 'dot_thi':
                $query->where('dot_thi_id', $value);
                return $query;
            case 'nam':
                $query->whereRelation('Examinations', 'nam',$value );
                return $query;
            case 'cap_don_vi':
                $query->whereRelation('Objects', 'cap_don_vi',$value );
                return $query;
            case 'don_vi':
                $query->where('doi_tuong_don_vi', $value);
                return $query;
            case 'type_order':
                $query->orderBy('diem','DESC');
                return $query;
            case 'type_order_1':
                $query->orderBy('thoi_gian_lam_bai','ASC');
                return $query;
            default:
                return $query;
        }
    }
    public function Examinations()
    {
        return $this->hasOne(ExaminationsModel::class, 'id', 'dot_thi_id');
    }
    public function Objects()
    {
        return $this->hasMany(ObjectsModel::class, 'id', 'doi_tuong_id');
    }
}
