<?php

namespace Modules\System\Objects\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;
use Modules\System\Listtype\Models\ListtypeModel;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObjectsModel extends Model
{

    protected $table = 'doi_tuong';
    public $incrementing = false;
    public $timestamps = false;
    // public $sortable = ['order'];

    protected $fillable = [
        'id',
        'ho_ten',
        'cmnd',
        'email',
        'don_vi',
        'trang_thai',
        'password',
        'last_login_at',
        'cap_don_vi',
        'created_at',
        'updated_at'
    ];

    public function filter($query, $param, $value)
    {
        switch ($param) {
            case 'search':
                $this->value = $value;
                return $query->where(function ($query) {
                    $query->where('ho_ten', 'like', '%' . $this->value . '%')
                          ->orWhere('cmnd', 'like', '%' . $this->value . '%')
                          ->orWhere('email', 'like', '%' . $this->value . '%');
                });       
                return $query;
            case 'role':
                $query->where('role', $value);
                return $query;
            case 'cap_don_vi':
                $query->where('cap_don_vi', $value);
                return $query;
            case 'don_vi':
                $query->where('don_vi', $value);
                return $query;
            case 'ngay_bat_dau':
                if(!empty($value)){
                    $query->whereDate('created_at', '>=',$value);
                    return $query;
                }
            case 'ngay_ket_thuc':
                if(!empty($value)){
                    $query->whereDate('created_at', '<=',$value);
                    return $query;
                }
            // case 'nam':
            //     $query->where('nam', $value);
            //     return $query;
            default:
                return $query;
        }
    }
}
