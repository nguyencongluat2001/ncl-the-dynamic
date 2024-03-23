<?php

namespace Modules\Frontend\Models;


use Illuminate\Database\Eloquent\Model;

class HealthModel extends Model
{
    protected $table = 'health_certificate';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'email',
        'phone',
        'date_of_birth',
        'sex',
        'address',
        'height',
        'weighed',
        'text',
        'history_of_pathology',
        'image',
        'trang_thai',
        'created_at',
        'updated_at'
    ];
}
