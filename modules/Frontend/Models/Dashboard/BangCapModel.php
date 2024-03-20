<?php

namespace Modules\Frontend\Models\Dashboard;

use Illuminate\Database\Eloquent\Model;
// use Modules\Core\Ncl\Http\BaseModel;

class BangCapModel extends Model
{
    protected $table = 'degree_education';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'code_category',
        'name',
        'email',
        'phone',
        'date_of_birth',
        'sex',
        'address',
        'school',
        'industry',
        'graduate_time',
        'level',
        'permanent_residence',
        'identity',
        'identity_time',
        'identity_address',
        'image',
        'image_transfer',
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
            case 'cate':
                $query->where('code_category', $value);
                return $query;
            default:
                return $query;
        }
    }
}
