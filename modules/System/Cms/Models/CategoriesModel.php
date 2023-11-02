<?php

namespace Modules\System\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

class CategoriesModel extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'parent_id',
        'name',
        'id_menu',
        'slug',
        'layout',
        'icon',
        'category_type',
        'owner_code',
        'is_display_at_home',
        'status',
        'order',
        'created_at',
        'updated_at',
    ];
}
