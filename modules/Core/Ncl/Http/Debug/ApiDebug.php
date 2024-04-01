<?php

namespace Modules\Core\Ncl\Http\Debug;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response as IlluminateResponse;
use DB;

/**
 * ApiDebug.
 * 
 * @author test <skype: test1505>
 */
class ApiDebug
{

    public static function check()
    {
        if (config('app.debug')) {
            return true;
        }
        return false;
    }

    public static function getDbName($type)
    {
        return DB::connection($type)->getDatabaseName();
    }

    public static function getDbLog($type)
    {
        $datas = DB::connection($type)->getQueryLog();
        $i = 0;
        $return = array();
        foreach ($datas as $data) {
            $query = $data['query'];
            $bindings = $data['bindings'];
            $query = str_replace('%', '$', $query);
            $sql = str_replace('?', "'%s'", $query);
            $sql = (string)sprintf($sql, ...$bindings);
            $sql = str_replace('$', '%', $sql);
            $return[$i]['query'] = $sql;
            $return[$i]['time'] = $data['time'];
            $i++;
        }
        return  $return;
    }

    public static function setDbLog()
    {
        DB::connection("sqlsrv")->enableQueryLog();
    }
}
