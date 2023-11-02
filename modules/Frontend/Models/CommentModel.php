<?php

namespace Modules\Frontend\Models;

use Illuminate\Database\Eloquent\Model;

class CommentModel extends Model
{
    protected $table = 'comments';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'parent_id',
        'articles_id',
        'name',
        'email',
        'phone',
        'address',
        'title',
        'content',
        'file_name',
        'file_url',
        'order',
        'status',
        'created_at',
        'updated_at',
    ];
}