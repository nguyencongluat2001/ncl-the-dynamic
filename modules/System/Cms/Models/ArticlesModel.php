<?php

namespace Modules\System\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use DB;

class ArticlesModel extends Model
{
    protected $table = 'tin_bai';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;

    public $sortable = ['order'];

    protected $fillable = [
        'id',
        'categories_id',
        'users_id',
        'users_name',
        'source',
        'create_date',
        'title',
        'author',
        'subject',
        'slug',
        'file_name',
        'feature_img',
        'note_feature_img',
        'content',
        'status_articles',
        'is_comment',
        'is_hide_relate_articles',
        'title_SEO',
        'description_SEO',
        'articles_type',
        'owner_code',
        'status',
        'order',
        'created_at',
        'updated_at',
    ];

    /**
     * @param mixed $query
     * @param mixed $param
     * @param mixed $value
     *
     * @return Query
     */
    public function filter($query, $param, $value)
    {
        switch($param){
            case 'id':
                return $query->where('id', $value);
            case 'category':
                $query->where('categories_id', $value);
                return $query;
            case 'articles_type':
                $query->where('articles_type', $value);
                return $query;
            case 'status':
                $query->where('status', $value);
                return $query;
            case 'fromdate':
                $query->whereDate('created_at', '>=', $value);
                return $query;
            case 'todate':
                $query->whereDate('created_at', '<=', $value);
                return $query;
            case 'search':
                $query->where('title', 'LIKE','%' . $value .'%')->orWhere('author', 'LIKE','%' . $value .'%');
                return $query;
            default:
                return $query;
        }
    }
    public function categories()
    {
        return $this->hasOne(CategoriesModel::class, 'id', 'categories_id');
    }
}
