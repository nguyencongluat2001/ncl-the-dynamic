<?php

namespace Modules\System\Sql\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\System\Category\Models\CategoryModel;

class SqlModel extends Model
{
    // protected $table = 'faq';
    // public $incrementing = false;
    // public $timestamps = false;
    // public $sortable = ['parent_id', 'order'];

    // protected $fillable = [
    //     'id',
    //     'parent_id',
    //     'question',
    //     'answer',
    //     'order',
    //     'status',
    //     'created_at',
    //     'updated_at'
    // ];

    // public function filter($query, $param, $value)
    // {
    //     switch ($param) {
    //         case 'id':
    //             return $query->where('id', $value);
    //         case 'parent_id':
    //             return $query->where('parent_id', $value);
    //         case 'search':
    //             return $query->where(function($sql) use($value){
    //                 $sql->where('question', 'like', "%$value%")
    //                     ->orWhere('answer', 'like', "%$value%");
    //             });
    //         default: return $query;
    //     }
    // }
    // public function parent()
    // {
    //     return $this->hasOne(CategoryModel::class, 'id', 'parent_id');
    // }
}
