<?php

namespace Modules\Base;

class Library
{
    public static function _dirToArray($dir, $code)
    {
        $dir = base_path() . $dir;
        $result = array();
        $cdir = scandir($dir);
        $i = 0;
        foreach ($cdir as $key => $value) {
            if (!in_array($value, array(".", ".."))) {
                if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                    $result[$i][$code] = self::_dirToArray($dir . DIRECTORY_SEPARATOR . $value);
                } else {
                    //print_r($value); exit;
                    $result[$i]['code'] = (string) $value;
                    $i++;
                }
            }
        }

        return $result;
    }

    public static function _createFolder($pathLink, $folderYear, $folderMonth, $sCurrentDay = "")
    {
        $sPath = str_replace("/", "\\", $pathLink);
        if (!file_exists($sPath . $folderYear)) {
            if(!file_exists($sPath)) {
                mkdir($sPath);
            }
            mkdir($sPath . $folderYear, 0777);
            $sPath = $sPath . $folderYear;
            if (!file_exists($sPath . chr(92) . $folderMonth)) {
                mkdir($sPath . chr(92) . $folderMonth, 0777);
            }
        } else {
            $sPath = $sPath . $folderYear;
            if (!file_exists($sPath . chr(92) . $folderMonth)) {
                mkdir($sPath . chr(92) . $folderMonth, 0777);
            }
        }
        //Tao ngay trong nam->thang
        if (!file_exists($sPath . chr(92) . $folderMonth . chr(92) . $sCurrentDay)) {
            mkdir($sPath . chr(92) . $folderMonth . chr(92) . $sCurrentDay, 0777);
        }
        //
        $strReturn = $pathLink . $folderYear . '/' . $folderMonth . '/' . $sCurrentDay . '/';

        return str_replace("/", "\\", $strReturn);
    }

    public static function _get_randon_number()
    {
        $ret_value = mt_rand(1, 1000000);

        return $ret_value;
    }

    public static function _get_randon_number_100_999()
    {
        $ret_value = mt_rand(100, 999);

        return $ret_value;
    }

    /**
     * Loại bỏ ký tự đặc biệt
     * 
     * @param string $spString
     * @return string Chuỗi gồm các ký tự: 0-9 a-z A-Z . _ - ! ~
     */
    public static function _replaceBadChar($spString)
    {
        $psRetValue = stripslashes($spString);
        $pattern = '/[^A-Za-z0-9\.\-\!\~\_]/';

        return preg_replace($pattern, '', $psRetValue);
    }

    public static function _convertVNtoEN($strText)
    {
        $vnChars = array("á", "à", "ả", "ã", "ạ", "ă", "ắ", "ằ", "ẳ", "ẵ", "ặ", "â", "ấ", "ầ", "ẩ", "ẫ", "ậ", "é", "è", "ẻ", "ẽ", "ẹ", "ê", "ế", "ề", "ể", "ễ", "ệ", "í", "ì", "ì", "ỉ", "ĩ", "ị", "ó", "ò", "ỏ", "õ", "ọ", "ô", "ố", "ồ", "ổ", "ỗ", "ộ", "ơ", "ớ", "ờ", "ở", "ỡ", "ợ", "ú", "ù", "ủ", "ũ", "ụ", "ư", "ứ", "ừ", "ử", "ữ", "ự", "ý", "ỳ", "ỷ", "ỹ", "ỵ", "đ", "Á", "﻿À", "Ả", "Ã", "Ạ", "Ă", "Ắ", "Ằ", "Ẳ", "Ẵ", "Ặ", "Â", "Ấ", "Ầ", "Ẩ", "Ẫ", "Ậ", "É", "È", "Ẻ", "Ẽ", "Ẹ", "Ê", "Ế", "Ề", "Ể", "Ễ", "Ệ", "Í", "Ì", "Ỉ", "Ĩ", "Ị", "Ó", "Ò", "Ỏ", "Õ", "Ọ", "Ô", "Ố", "Ồ", "Ổ", "Ỗ", "Ộ", "Ơ", "Ớ", "Ờ", "Ở", "Ỡ", "Ợ", "Ú", "Ù", "Ủ", "Ũ", "Ụ", "Ư", "Ứ", "Ừ", "Ử", "Ữ", "Ự", "Ý", "Ỳ", "Ỷ", "Ỹ", "Ỵ", "Đ");
        $enChars = array("a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "i", "i", "i", "i", "i", "i", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "y", "y", "y", "y", "y", "d", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "I", "I", "I", "I", "I", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "Y", "Y", "Y", "Y", "Y", "D");
        for ($i = 0; $i < sizeof($vnChars); $i++) {
            $strText = str_replace($vnChars[$i], $enChars[$i], $strText);
        }

        return $strText;
    }

    public function _generateHtmlForMultipleCheckbox($arrList, $IdColumn, $NameColumn, $Valuelist, $sformFielName, $delemiter, $label)
    {
        $arr_value = explode($delemiter, $Valuelist);
        $count_value = sizeof($arr_value);
        $strHTML = '';
        $strHTML = $strHTML . '<table id="' . $sformFielName . '" class="griddata" width="100%" cellspacing="0" cellpadding="0">';
        $strHTML = $strHTML . '<colgroup><col width="5%"><col width="28%"><col width="5%"><col width="28%"><col width="5%"><col width="29%"></colgroup>';
        $strHTML = $strHTML . '<tr class="header">';
        $strHTML = $strHTML . "<td><input type='checkbox' name='checkall_process_per_group' onclick='checkallper(this,\"$sformFielName\")' /></td>";
        $strHTML = $strHTML . '<td colspan="5">' . $label . '</td>';
        $strHTML = $strHTML . '</tr>';
        $data = $arrList;
        $iTotal = sizeof($data);

        for ($i = 0; $i < $iTotal; $i++) {
            if ($i % 3 == 0) {
                $strHTML .= '<tr>';
                $flag = 0;
            }
            $id = $data[$i][$IdColumn];
            $namecolum = $data[$i][$NameColumn];
            $colspan = '';
            if (($i + 1) == $iTotal) {
                if ($flag == 0)
                    $colspan = 'colspan="5"';
                if ($flag == 1)
                    $colspan = 'colspan="3"';
            }
            $v_item_checked = "";
            for ($j = 0; $j < $count_value; $j++) {
                if ($arr_value[$j] == $id) {
                    $v_item_checked = "checked";
                    break;
                }
            }
            $strHTML .= "<td align='center'><input $v_item_checked type='checkbox' class='$sformFielName' name='$sformFielName' id='" . $sformFielName . $i . "' value='" . $id . "'/></td>";
            $strHTML .= '<td ' . $colspan . '><label style="text-align: left;" class="normal_label" for="' . $sformFielName . $i . '">' . $namecolum . '</label></td>';
            if (($i + 1) % 3 == 0)
                $strHTML .= '</tr>';
            $flag++;
        }
        $strHTML .= "</table>";

        return $strHTML;
    }

}
