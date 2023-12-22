<?php

namespace Modules\System\Helpers;

class NclLibraryHelper
{
    public static function _ddmmyyyyToyyyymmdd($psDdmmyyyy, $iSearch = '', $type = '/')
    {
        $psdate = NULL;
        $psdateArr = "";
        $psdel = "";
        if (strlen($psDdmmyyyy) == 0 or is_null($psDdmmyyyy)) {
            $psdate = "";
            return $psdate;
        }
        if (strpos($psDdmmyyyy, "-") <= 0 and strpos($psDdmmyyyy, "/") <= 0) {
            $psdate = "";
            return $psdate;
        }
        if (strpos($psDdmmyyyy, "-") > 0) {
            $psdel = "-";
        }
        if (strpos($psDdmmyyyy, "/") > 0) {
            $psdel = "/";
        }
        $arr = explode(" ", $psDdmmyyyy);
        if ($arr[0] <> "") {
            $psdateArr = explode($psdel, $arr[0]);
            if (sizeof($psdateArr) <> 3) {
                $psdate = NULL;
                return $psdate;
            } else {
                if ($iSearch == 3)
                    $psdate = $psdateArr[2] . $type . $psdateArr[1] . $type . $psdateArr[0] . (!empty($arr[1]) ? ' ' . $arr[1] : '');
                else if ($iSearch == 2)
                    $psdate = $psdateArr[2] . $type . $psdateArr[1] . $type . $psdateArr[0] . ' ' . gmdate("23:59:59");
                else if ($iSearch == 1)
                    $psdate = $psdateArr[2] . $type . $psdateArr[1] . $type . $psdateArr[0];
                else
                    $psdate = $psdateArr[2] . $type . $psdateArr[1] . $type . $psdateArr[0] . ' ' . gmdate("H:i:s", time() + 3600 * (7 + date("0")));
                return $psdate;
            }
        }
        return $psdate;
    }
}