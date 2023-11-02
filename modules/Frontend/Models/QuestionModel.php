<?php

namespace Modules\Frontend\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Bảng câu hỏi
 * 
 * @author khuongtq
 */
class QuestionModel extends Model
{
    protected $table = 'cau_hoi';
    protected $keyType = 'string';
    public $incrementing = false;

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
        'updated_at',
    ];
}
