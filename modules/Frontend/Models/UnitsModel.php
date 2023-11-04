<?php

namespace Modules\Frontend\Models;


use Illuminate\Database\Eloquent\Model;

class UnitsModel extends Model
{
    protected $table = 'units';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'code_tinh',
        'code_huyen',
        'code_xa',
        'name',
        'name_type'
    ];
}
