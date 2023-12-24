<?php

namespace Modules\Frontend\Models\Dashboard;

use Illuminate\Database\Eloquent\Model;
// use Modules\Core\Ncl\Http\BaseModel;

class BlogDetailModel extends Model
{
    protected $table = 'blogs_details';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'code_blog',
        'title',
        'decision', 
        'rate',
        'created_at',
        'updated_at'
    ];
}
