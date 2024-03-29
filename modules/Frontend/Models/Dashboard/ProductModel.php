<?php

namespace Modules\Frontend\Models\Dashboard;

use Illuminate\Database\Eloquent\Model;
// use Modules\Core\Ncl\Http\BaseModel;

class ProductModel extends Model
{
    protected $table = 'health_certificate';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'email',
        'phone',
        'date_of_birth',
        'sex',
        'address',
        'height',
        'weighed',
        'history_of_pathology',
        'image',
        'trang_thai',
        'created_at',
        'updated_at'
    ];

    public function filter($query, $param, $value)
    {
        switch ($param) {
            case 'search':
                $this->value = $value;
                return $query->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->value . '%')
                        ->orWhere('email', 'like', '%' . $this->value . '%')
                        ->orWhere('phone', 'like', '%' . $this->value . '%')
                        ->orWhere('address', 'like', '%' . $this->value . '%');
                });
                return $query;
                // case 'role':
                //     // $query->where('role', $value);
                //     return $query;
            default:
                return $query;
        }
    }
}
