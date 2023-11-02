<?php

namespace Modules\Core\Efy;

// Date
use Modules\Core\Efy\Date\Helper as Efy_Date_Helper;
use Modules\Core\Efy\Date\SolarLular as Efy_Date_SolarLular;
// Db
use Modules\Core\Efy\Db\Connection as Efy_Db_Connection;

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
            case 'Efy_Db_Connection':
                return new Efy_Db_Connection();
                // Date
            case 'Efy_Date_Helper':
                return new Efy_Date_Helper();
            case 'Efy_Date_SolarLular':
                return new Efy_Date_SolarLular();
            default:
                throw new \Exception('Class ' . $obj . ' not found');
        }
    }
}
