<?php

namespace Modules\Core\Ncl\Date;

use DateTime;
use Config;

/**
 * Lớp xử lý về ngày tháng, thời gian.
 *
 * @author Toanph <skype: toanph1505>
 */
class DateHelper
{
    /*
    | updated at 09/07/2022
    | luatnc
    */

    /**
     * Kiểm tra dữ liệu có phải một trong các format đã định trước
     */
    public static function checkDateFormat($date)
    {
        $arrFormat = [
            'm/d/Y', 'd/m/Y', 'm/Y', 'Y',
            'd-m-Y', 'm-Y',
            'd.m.Y', 'm.Y',
            'Y/m/d', 'Y/m', 'Y/m/d H:i:s',
            'Y-m-d', 'Y-m', 'Y-m-d H:i:s',
            'Y.m.d', 'Y.m',
            'Ymd', 'YmdHis',
            'dmY', 'dmYHis',
        ];
        foreach ($arrFormat as $format) {
            $dt = \DateTime::createFromFormat($format, $date);
            if ($dt && $dt->format($format) === $date) {
                return $format;
            }
        }

        return false;
    }

    /**
     * Convert sang các dạng ['d.m.Y', 'm.Y', 'Y']
     *
     * @param string $date Đầu vào bắt buộc dạng ['d/m/Y', 'm/Y', 'Y']
     * @return string
     */
    public static function _dmY($date)
    {
        $arr = explode('/', $date);
        $return = '';
        for ($i = 0; $i < count($arr); $i++) {
            $return .= $arr[$i];
            if ($i != count($arr) - 1) {
                $return .= '.';
            }
        }

        return $return;
    }

    /**
     * Convert chuỗi date từ format input và trả về với format output
     *
     * @param string $date Chuỗi ngày tháng truyền vào
     * @param string $inputFormat Định dạng ngày tháng truyền vào
     * @param string $outputFormat Định dạng ngày tháng muốn lấy
     * @return string $dateReturn Chuỗi ngày tháng đã convert nếu đúng định dạng và tham số,
     * nếu sai trả về chính ngày tháng truyền vào
     */
    public static function _date($date, $inputFormat = 'Y-m-d', $ouputFormat = 'd/m/Y')
    {
        $dt = \DateTime::createFromFormat($inputFormat, $date);
        if ($dt && $dt->format($inputFormat) === $date) {
            $dateReturn = $dt->format($ouputFormat);
        } else {
            $dateReturn = $date;
        }

        return $dateReturn;
    }

    /**
     * Convert chuỗi date từ format input và trả về với format output
     *
     * @param string $date Chuỗi ngày tháng truyền vào định dạng: yyyyMMdd hh:mm:ss
     * @return string  Chuỗi ngày tháng đã convert
     */
    public static function formatStringNumber($date)
    {
        $arrDateTime = explode(' ', $date);
        if (!isset($arrDateTime[0]) || !isset($arrDateTime[1])) {
            return $date;
        }
        $date = $arrDateTime[0];
        $time = $arrDateTime[1];
        $dateArr = explode('-', $date);
        $timeArr = explode(':', $time);
        $dateString = $dateArr[0] . $dateArr[1] . $dateArr[2];
        $timeString = $timeArr[0] . $timeArr[1] . $timeArr[2];
        $timeString = explode('.', $timeString)[0];
        return $dateString . $timeString; //Ex:20181120221912
    }

    /**
     * Lấy ngày cuối cùng của tháng năm hiện tại
     * 
     * @return string
     */
    public function createLastDay()
    {
        $currentDate = date('Y-m-d');
        $lastDayOfMonth = date('t', strtotime($currentDate));
        return date($lastDayOfMonth . '/m/Y');
    }
}
