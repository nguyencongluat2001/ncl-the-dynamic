<?php

namespace Modules\System\Examinations\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;
use Modules\System\Listtype\Models\ListtypeModel;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionsModel extends Model
{

    protected $table = 'cau_hoi';
    public $incrementing = false;
    public $timestamps = false;
    // public $sortable = ['thu_tu'];

    protected $fillable = [
        'id',
        'nguoi_tao_id',
        'dot_thi_id',
        'ten_cau_hoi',
        'dap_an_a',
        'dap_an_b',
        'dap_an_c',
        'dap_an_d',
        'dap_an_dung',
        'trang_thai',
        'thu_tu',
        'created_at',
        'updated_at'
    ];

    public function filter($query, $param, $value)
    {
        switch ($param) {
            case 'search':
                $this->value = $value;
                return $query->where(function ($query) {
                    $query->where('ten_cau_hoi', 'like', '%' . $this->value . '%');
                });       
                return $query;
            case 'role':
                $query->where('role', $value);
                return $query;
            case 'dot_thi_id':
                $query->where('dot_thi_id', $value);
                return $query;
            case 'type_order':
                $query->orderBy('thu_tu','ASC');
                return $query;
            case 'trang_thai':
                $query->where('trang_thai', $value);
                return $query;
            // case 'nam':
            //     $query->where('nam', $value);
            //     return $query;
            default:
                return $query;
        }
    }
}
