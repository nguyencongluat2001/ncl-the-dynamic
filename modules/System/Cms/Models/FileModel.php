<?php

namespace Modules\System\Cms\Models;

use Illuminate\Database\Eloquent\Model;

class FileModel extends Model
{
    protected $table = 'files';
    public $incrementing = false;
    public $timestamps = false;
}