<?php

namespace Modules\Core\Efy\Date;

/**
 * Lớp xử lý về ngày tháng, thời gian.
 *
 * @author Toanph <skype: toanph1505>
 */
class Helper
{
    /**
     * Hình thức tính ngày xử lý theo NGÀY LÀM VIỆC
     * * Không tính thứ 7, chủ nhật và các ngày lễ/tết
     */
    const TYPE_PROCESS_01 = 'NGAY_LAM_VIEC';

    /**
     * Hình thức tính ngày xử lý theo NGÀY
     * * Có tính thứ 7, chủ nhật
     * * Không tính các ngày lễ/tết
     */
    const TYPE_PROCESS_02 = 'NGAY';

    /**
     * Hình thức tính ngày xử lý theo GIỜ
     * * Có tính thứ 7, chủ nhật
     * * Không tính các ngày lễ/tết
     */
    const TYPE_PROCESS_03 = 'GIO';

    protected $holidays;

    /**
     * Hình thức/cách tính ngày xử lý
     * 
     * ---
     * 
     * * Xử lý theo ngày làm việc: `NGAY_LAM_VIEC`
     * * Xử lý theo ngày (cả t7,CN): `NGAY`
     * * Xử lý theo giờ (cả t7,CN): `GIO`
     */
    protected $type;

    /**
     * Set Hình thức/cách tính ngày xử lý
     * 
     * ---
     * 
     * * Xử lý theo ngày làm việc: `NGAY_LAM_VIEC`
     * * Xử lý theo ngày (cả t7,CN): `NGAY`
     * * Xử lý theo giờ (cả t7,CN): `GIO`
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * Chuyển đổi thời gian dạng yyyymmdd thành dạng dd/mm/yyyy
     *
     * @param $Ymd: thời gian dạng string
     * @return string dạng dd/mm/yyyy (Để hiển thị trên màn hình)
     */
    public function _yyyymmddToDDmmyyyy($Ymd)
    {
        if (is_null($Ymd) || trim($Ymd) == "" || $Ymd == 1900)
            return "";
        return date("d/m/Y", strtotime($Ymd));
    }

    /**
     * Chuyển đổi ngày dạng mm/dd/yyyy thành ngày dạng yyyy/mm/dd
     * Edit : Nghiat
     * @param $psDdmmyyyy : la chuoi dang dd/mm/yyyy( chuoi chu khong phai date object )
     * $iSearch = 1 : SU dung de phuc vu tim kiem tu ngay 
     * 			= 2	: SU dung de phuc vu tim kiem den ngay
     * 			= '' or (!= 1 && != 2 ) : Su dung de update kieu dd/mm/yyyy h:i:s vao CSDL	
     * @return chuoi dang yyyy/mm/dd(De dua vao csdl)
     */
    public function _ddmmyyyyToYYyymmdd($psDdmmyyyy, $iSearch = '')
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
                if ($iSearch == 2)
                    $psdate = $psdateArr[2] . "/" . $psdateArr[1] . "/" . $psdateArr[0] . ' ' . gmdate("23:59:59");
                else if ($iSearch == 1)
                    $psdate = $psdateArr[2] . "/" . $psdateArr[1] . "/" . $psdateArr[0];
                else
                    $psdate = $psdateArr[2] . "/" . $psdateArr[1] . "/" . $psdateArr[0] . ' ' . gmdate("H:i:s", time() + 3600 * (7 + date("0")));
                return $psdate;
            }
        }
        return $psdate;
    }
    /**
     * Lấy Ngày hiện tại trong tuần (Thứ) theo Tiếng Việt
     * 
     * @param unknown_type $p_lang
     */
    function _generate_day_of_week($p_lang)
    {
        $RetStr = date("l, d/m/y");
        $pday = date("l");
        if ($p_lang == 1) {
            switch ($pday) {
                case "Sunday";
                    $RetStr = "Chủ nhật";
                    break;
                case "Monday";
                    $RetStr = "Thứ hai";
                    break;
                case "Tuesday";
                    $RetStr = "Thứ ba";
                    break;
                case "Wednesday";
                    $RetStr = "Thứ tư";
                    break;
                case "Thursday";
                    $RetStr = "Thứ năm";
                    break;
                case "Friday";
                    $RetStr = "Thứ sáu";
                    break;
                case "Saturday";
                    $RetStr = "Thứ bảy";
            }
            $RetStr = $RetStr . ", " . date("d/m/Y");
        }
        return $RetStr;
    }

    function getNumberProcessByTwoDate($startDate, $endDate)
    {
        $SolarLular = new SolarLular();

        // do strtotime calculations just once
        $endDate = strtotime($endDate);
        $startDate = strtotime($startDate);

        // The total number of days between the two dates. 
        // We compute the no. of seconds and divide it to 60*60*24
        // We add one to inlude both dates in the interval.
        $days = ($endDate - $startDate) / 86400; // + 1;

        $no_full_weeks = floor($days / 7);
        $no_remaining_days = fmod($days, 7);

        // It will return 1 if it's Monday,.. ,7 for Sunday
        $the_first_day_of_week = date("N", $startDate);
        $the_last_day_of_week = date("N", $endDate);

        // ---->The two can be equal in leap years when february has 29 days, 
        // the equal sign is added here
        // In the first case the whole interval is within a week, 
        // in the second case the interval falls in two weeks.
        if ($the_first_day_of_week <= $the_last_day_of_week) {
            if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
            if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
        } else {
            // (edit by Tokes to fix an edge case where the start day was a Sunday
            // and the end day was NOT a Saturday)
            // the day of the week for start is later than the day of the week for end
            if ($the_first_day_of_week == 7) {
                // if the start date is a Sunday, then we definitely subtract 1 day
                $no_remaining_days--;
                if ($the_last_day_of_week == 6) {
                    // if the end date is a Saturday, then we subtract another day
                    $no_remaining_days--;
                }
            } else {
                // the start date was a Saturday (or earlier), and the end date was (Mon..Fri)
                // so we skip an entire weekend and subtract 2 days
                $no_remaining_days -= 2;
            }
        }

        // The no. of business days is: 
        // (number of weeks between the two dates) * (5 working days) + the remainder
        // ---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
        $workingDays = $no_full_weeks * 5;
        if ($no_remaining_days > 0) {
            $workingDays += $no_remaining_days;
        }

        $strHoliday = $SolarLular->getHolidays(Date('Y'));
        $arrHolidays = explode(',', $strHoliday);
        $arrHolidays = array_map(function ($v) {
            return date('Y-m-d', strtotime($v));
        }, $arrHolidays);
        sort($arrHolidays);
        $arrHolidays = array_unique($arrHolidays);

        // We subtract the holidays
        foreach ($arrHolidays as $holiday) {
            $time_stamp = strtotime($holiday);
            // If the holiday doesn't fall in weekend
            if ($startDate <= $time_stamp && $time_stamp <= $endDate && date("N", $time_stamp) != 6 && date("N", $time_stamp) != 7)
                $workingDays--;
        }

        return (int) $workingDays;
    }

    /**
     * Tính ngày hẹn
     * 
     * Cộng thêm N ngày vào ngày ban đầu
     * 
     * @param string $begindate Ngày bắt đầu
     * @param string $numberdate Ngày cộng thêm
     */
    public function _get_appointe_date($begindate, $numberdate)
    {
        $SolarLular = new SolarLular();
        $this->holidays = $SolarLular->getHolidays(Date('Y'));
        $arrHolidays = explode(',', $this->holidays);
        for ($ii = 0; $ii < sizeof($arrHolidays); $ii++) {
            $arrHolidays[$ii] = date("Y/m/d", strtotime($arrHolidays[$ii]));
        }
        sort($arrHolidays);
        $arrHolidays = array_unique($arrHolidays);
        // bỏ các ngày nghỉ nếu trùng vào T7 CN (vì các ngày nghỉ bù đã config bằng tay trong file config)
        foreach ($arrHolidays as $k => $v) {
            if ($this->_getday($v) == 'Sat' || $this->_getday($v) == 'Sun') unset($arrHolidays[$k]);
        }
        if ($numberdate > 0) {
            $i = 1;
            // không tính ngày nghỉ lễ - Tết
            while ($i <= $numberdate) {
                // Nếu tính theo ngày -> tính cả t7, CN
                if ($this->type == self::TYPE_PROCESS_02) {
                    $begindate = $this->_dateAdd($begindate, 1);
                    if (!$this->_checkInHolidays($begindate, $arrHolidays)) {
                        $i++;
                    }
                }
                // Nếu tính theo giờ -> tính cả t7, CN
                elseif ($this->type == self::TYPE_PROCESS_03) {
                    $begindate = $this->_dateAdd($begindate, 1, 'hours');
                    if (!$this->_checkInHolidays($begindate, $arrHolidays)) {
                        $i++;
                    }
                }
                // Nếu tính theo ngày làm việc -> không tính t7, CN
                else {
                    $begindate = $this->_dateAdd($begindate, 1);
                    // $begindate = $this->_dateaddbyday($begindate, 1);
                    if ($this->_getday($begindate) != 'Sat' && $this->_getday($begindate) != 'Sun') {
                        if (!$this->_checkInHolidays($begindate, $arrHolidays)) {
                            $i++;
                        }
                    } else {
                        if ($this->_checkInHolidays($begindate, $arrHolidays)) {
                            $i--;
                        }
                    }
                }
            }
        }

        return $begindate;
    }

    /**
     * Cộng thêm ngày hoặc giờ
     * ---
     * $type = 'days' or 'hours'
     * 
     * @editor khuongtq
     */
    public function _dateAdd(string $dateInput, int $numberDay, string $type = 'days'): string
    {
        $date = new \DateTime($dateInput);
        $date->modify('+' . $numberDay . ' ' . $type);

        return $date->format('Y-m-d H:i:s');
    }

    public function _getday($date)
    {
        return date("D", strtotime($date));
    }

    public function _checkInHolidays($date, $arrHolidays)
    {
        $date = date("Y/m/d", strtotime($date));
        if (in_array($date, $arrHolidays))
            return true;
        else
            return false;
    }
    public function _getdatediff($date1, $date2)
    {
        // dd($date1, $date2);
        $diff = \date_diff(date_create($date1), date_create($date2));
        $R = $diff->format('%R');
        $A = $diff->format('%a');
        if ($R == '+') {
            return 1 * $A;
        } else {
            return (-1) * $A;
        }
    }
}
