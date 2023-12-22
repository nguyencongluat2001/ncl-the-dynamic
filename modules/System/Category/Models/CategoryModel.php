<?php

namespace Modules\System\Category\Models;

use Illuminate\Database\Eloquent\Model;
// use Modules\Core\Ncl\Http\BaseModel;

class CategoryModel extends Model
{
    protected $table = 'categorys';
    public $incrementing = false;
    public $timestamps = false;
    public $sortable = ['order'];

    protected $fillable = [
        'id',
        'cate',
        'name_category',
        'code_category',
        'decision',
        'order',
        'status',
        'created_at',
        'updated_at'
    ];

    public function filter($query, $param, $value)
    {
        switch ($param) {
            case 'search':
                $this->value = $value;
                // dd($this->value);
                return $query->where(function ($query) {
                    $query->where('name_category', 'like', '%' . $this->value . '%')
                          ->orWhere('cate','like', '%' . $this->value . '%')
                          ->orWhere('code_category','like', '%' . $this->value . '%')
                          ->orWhere('decision','like', '%' . $this->value . '%');
                });
                return $query;
            case 'cate':
                $query->where('cate', $value);
                return $query;
            default:
                return $query;
        }
    }
}
