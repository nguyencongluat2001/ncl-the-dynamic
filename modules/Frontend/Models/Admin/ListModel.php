<?php

namespace Modules\Frontend\Models\Admin;

use Illuminate\Database\Eloquent\Model;

/**
 * Đối tượng danh mục
 * 
 * @author khunogtq
 */
class ListModel extends Model
{
    protected $table = 'system_list';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'system_listtype_id',
        'code',
        'name',
        'order',
        'status',
        'owner_code_list',
        'xml_data',
        'created_at',
        'updated_at',
    ];
}
