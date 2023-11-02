<?php

namespace Modules\System\Exams\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Bảng bài thi
 * 
 * @author khuongtq
 */
class ExamDetailModel extends Model
{
    protected $table = 'bai_thi_chi_tiet';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'bai_thi_id',
        'cau_hoi_id',
        'dap_an_dung',
        'dap_an',
        'dap_an_random',
        'json_random',
        'ket_qua',
        'thu_tu',
        'created_at',
        'updated_at',
    ];
}
