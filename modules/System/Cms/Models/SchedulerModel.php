<?php

namespace Modules\System\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use DB;

class SchedulerModel extends Model {

    protected $table = 'SCHEDULE_UNIT';
    protected $primaryKey = 'PK_SCHEDULE_UNIT';
    public $incrementing = false;
    public $timestamps = false;

    public function _getAll($week, $year) {
        $sql = "EXEC [Schedule_UnitGetAll] '$week','$year','" . $_SESSION['OWNER_CODE'] . "','1'";
        $arrData = DB::select($sql);
        $arrData = array_map(function($v) {
            return (array) $v;
        }, $arrData);
        return $arrData;
    }

    public function _getAllFrontEnd($week, $year, $ownercode) {
        $sql = "EXEC [Schedule_UnitGetAll] '$week','$year','" . $ownercode . "','1'";
        $arrData = DB::select($sql);
        $arrData = array_map(function($v) {
            return (array) $v;
        }, $arrData);
        return $arrData;
    }

}
