<?php

namespace Modules\Core\Ncl\Db;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Modules\Core\Ncl\Exceptions\ResponseExeption;
use Modules\Api\Models\Admin\UnitModel;

/**
 * @author: test
 * Ket noi CSDL su dung Pdo
 */
class Connection
{

    public static function setConnection()
    {
        if (auth('sanctum')->user()) {
            $unitId = auth('sanctum')->user()->units_id;
            $unit = UnitModel::find($unitId);
            if ($unit && $unit->owner_code !== "") {
                try {
                    auth('sanctum')->user()->owner_code = $unit->owner_code;
                    Config::set('database.connections.sqlsrvEcs.database', $unit->owner_code);
                    DB::purge('sqlsrvEcs');
                } catch (Throwable $e) {
                    throw new ResponseExeption("DB" . $unit->owner_code . " Chưa định nghĩa");
                }
            } else {
                throw new ResponseExeption("Don vi: " . $unitId . " Chưa định nghĩa");
            }
        }
    }

    public static function setConnectionUnitPrint($code)
    {
        $unit = UnitModel::where("code", $code)->first();
        Config::set('database.connections.sqlsrvEcs.database', $unit->owner_code);
        DB::purge('sqlsrvEcs');
    }


    public static function setConnectionUnit($code)
    {
        Config::set('database.connections.sqlsrvEcs.database', $code);
        DB::purge('sqlsrvEcs');
    }

    public static function setConnectionTransition($database)
    {
        Config::set('database.connections.sqlsrvTransition.database', $database);
        DB::purge('sqlsrvTransition');
    }

    public static function setConnectionFileServerByYear($year = '')
    {
        if (empty($year)) $year = date('Y');
        $config = array();
        $configView = config('file.ViewFile');
        foreach ($configView as $v) {
            if ($v['type'] == 'FileServer' && array_search($year, $v['year']) !== false) {
                $config = $v;
                break;
            }
        };
        if (empty($config)) return false;
        Config::set('database.connections.sqlsrvFS.database', $config['database']);
        Config::set('database.connections.sqlsrvFS.host', $config['host']);
        Config::set('database.connections.sqlsrvFS.username', $config['username']);
        Config::set('database.connections.sqlsrvFS.password', $config['password']);
        DB::purge('sqlsrvFS');
        return true;
    }

    public static function connectPDO($ownercode)
    {
        Config::set('database.connections.sqlsrvEcs.database', $ownercode);
        DB::purge('sqlsrvEcs');
    }

    public function select($sql)
    {
        return DB::connection("sqlsrvEcs")->select($sql);
    }

    public function update($sql)
    {
        return DB::connection("sqlsrvEcs")->update($sql);
    }

    public function insert($sql)
    {
        return DB::connection("sqlsrvEcs")->insert($sql);
    }

    public function delete($sql)
    {
        return DB::connection("sqlsrvEcs")->delete($sql);
    }


    /**
     * Date:
     * Thuc thi hanh dong update / delete / getsingle / ...
     * @param $sql : Xau SQL can thuc thi
     * @return array Kết quả
     */
    public function selectToArray($sql)
    {
        return array_map(function ($value) {
            return (array) $value;
        }, DB::connection("sqlsrvEcs")->select($sql));
    }

    public function execSP($spName, $arrParameter, $sSingle = false, $sSql = false)
    {
        $sql = '';
        if (is_array($arrParameter)) {
            foreach ($arrParameter as $key => $value) {
                if ($sql != '') {
                    $sql .= ",N'" . $value . "'";
                } else {
                    $sql .= " N'" . $value . "'";
                }
            }
        }
        $sql = 'Exec [dbo].' . $spName . $sql;
        // dd($sql);
        if ($sSql) {
            return $sql;
        }

        return $this->selectToArray($sql);
    }

    public function execSPNoResult($spName, $arrParameter, $sSingle = false, $sSql = false)
    {
        $sql = '';
        if (is_array($arrParameter)) {
            foreach ($arrParameter as $key => $value) {
                if ($sql != '') {
                    $sql .= ",N'" . $value . "'";
                } else {
                    $sql .= " N'" . $value . "'";
                }
            }
        }
        $sql = 'Exec [dbo].' . $spName . $sql;
        // dd($sql);
        if ($sSql) {
            return $sql;
        }

        return DB::connection("sqlsrvEcs")->update($sql);
    }
}
