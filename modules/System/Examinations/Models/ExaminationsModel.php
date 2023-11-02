<?php

namespace Modules\System\Examinations\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;
use Modules\System\Listtype\Models\ListtypeModel;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExaminationsModel extends Model
{

    protected $table = 'dot_thi';
    public $incrementing = false;
    public $timestamps = false;
    public $sortable = ['order'];

    protected $fillable = [
        'id',
        'nguoi_tao_id',
        'ten',
        'nam',
        'ngay_bat_dau',
        'ngay_ket_thuc',
        'trang_thai',
        'thoi_gian_lam_bai',
        'created_at',
        'updated_at'
    ];

    public function filter($query, $param, $value)
    {
        switch ($param) {
            case 'search':
                $this->value = $value;
                return $query->where(function ($query) {
                    $query->where('ten', 'like', '%' . $this->value . '%');
                });       
                return $query;
            case 'role':
                $query->where('role', $value);
                return $query;
            case 'nam':
                $query->where('nam', $value);
                return $query;
            case 'type_order':
                $query->orderBy('created_at','ASC');
                return $query;
            default:
                return $query;
        }
    }
}
