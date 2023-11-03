<?php
namespace Modules\System\Cms\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use DB;
use Modules\Core\Ncl\Library;
use Uuid;

class ReaderModel extends Model{
    protected $table = "eLIB_Reader";
    protected $primaryKey = "PK_READER";
    public $timestamps = true;
    public $incrementing = false;

    const CREATED_AT = 'C_CREATE_AT';
    const UPDATED_AT = 'C_UPDATE_AT';

    
}
