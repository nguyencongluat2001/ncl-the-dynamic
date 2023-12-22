<?php

namespace Modules\System\Category\Models;

use Illuminate\Database\Eloquent\Model;
// use Modules\Core\Ncl\Http\BaseModel;

class CateModel extends Model
{
    protected $table = 'cates';
    public $incrementing = false;
    public $timestamps = false;
    public $sortable = ['order'];

    protected $fillable = [
        'id',
        'name',
        'code_cate',
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
                    $query->where('name', 'like', '%' . $this->value . '%')
                          ->orWhere('code_cate','like', '%' . $this->value . '%')
                          ->orWhere('decision','like', '%' . $this->value . '%');
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
