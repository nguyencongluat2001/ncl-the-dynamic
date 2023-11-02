<?php

namespace Modules\Frontend\Models\Admin;

use Illuminate\Database\Eloquent\Model;

/**
 * Danh mục
 * 
 * @author khuongtq
 */
class ListtypeModel extends Model
{
    protected $table = 'system_listtype';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'xml_file_name',
        'order',
        'owner_code_list',
        'status',
        'created_at',
        'updated_at',
    ];
}
