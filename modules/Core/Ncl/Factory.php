<?php

namespace Modules\Core\Ncl;

// Date
use Modules\Core\Ncl\Date\Helper as Ncl_Date_Helper;
use Modules\Core\Ncl\Date\SolarLular as Ncl_Date_SolarLular;
// Db
use Modules\Core\Ncl\Db\Connection as Ncl_Db_Connection;

/**
 * Khoi tao cac lop he thong.
 *
 * @author Toanph <skype: toanph1505>
 */
class Factory
{

    public static function loadClass($obj)
    {
        switch ($obj) {
                // Db
            case 'Ncl_Db_Connection':
                return new Ncl_Db_Connection();
                // Date
            case 'Ncl_Date_Helper':
                return new Ncl_Date_Helper();
            case 'Ncl_Date_SolarLular':
                return new Ncl_Date_SolarLular();
            default:
                throw new \Exception('Class ' . $obj . ' not found');
        }
    }
}
