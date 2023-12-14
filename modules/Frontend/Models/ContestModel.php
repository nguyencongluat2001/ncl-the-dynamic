<?php

namespace Modules\Frontend\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Bảng đợt thi
 * 
 * @author luatnc
 */
class ContestModel extends Model
{
    protected $table = 'dot_thi';
    protected $keyType = 'string';
    public $incrementing = false;

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
        'updated_at',
    ];
}
