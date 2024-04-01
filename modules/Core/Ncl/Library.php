<?php

namespace Modules\Core\Ncl;

/**
 * Thư viện dùng chung cho toàn dự án.
 *
 * @author test <skype: test1505>
 * @updated 26/12/2022
 * @editor luatnc
 */
class Library
{

    /**
     * Tạo các option của đối tượng selectbox từ một array
     *
     * @return string html
     */
    public function _generateSelectOption($arr_list, $IdColumn, $ValueColumn, $NameColumn, $SelectedValue = "")
    {
        $strHTML = "";
        $i = 0;
        $count = sizeof($arr_list);
        for ($row_index = 0; $row_index < $count; $row_index++) {
            $strID = trim($arr_list[$row_index][$IdColumn]);
            $strValue = trim($arr_list[$row_index][$ValueColumn]);
            $gt = $SelectedValue;
            if ($strID != $SelectedValue) {
                $optSelected = "";
            } else {
                $optSelected = "selected";
            }
            $DspColumn = trim($arr_list[$row_index][$NameColumn]);
            $strHTML .= '<option id=' . '"' . $strID . '"' . ' ' . 'name=' . '"' . $strID . '"' . ' ';
            $strHTML .= 'value=' . '"' . $strValue . '"' . ' ' . $optSelected . '>' . $DspColumn . '</option>';
            $i++;
        }

        return $strHTML;
    }

    /**
     * Load các file Js,Css từ controller
     *
     * @return array
     */
    public static function _getAllFileJavaScriptCssArray($psExtension, $parrFileName = "", $psDelimitor = ",", $result = array())
    {
        // Thuc hien lay file js o nhung module khac
        $filetype = strtolower($psExtension);
        $sResHtml = null;
        if ($filetype != "") {
            //
            $sDir = url('/' . $filetype);
            $file = '';
            $count = sizeof($result);
            $arrTemp = explode($psDelimitor, $parrFileName);
            //print_r($arrTemp); exit;
            for ($index = 0; $index < sizeof($arrTemp); $index++) {
                //Thuc hien include file JavaScript
                $filetypeinDirJs = @substr($arrTemp[$index], strlen($file) - 2, 2);
                $filetypeinDirJs = @strtolower($filetypeinDirJs);
                if ($filetype == "js" && $filetypeinDirJs == "js") {
                    $sDirFull = $sDir . "/" . $arrTemp[$index];
                    $result[$count]['type'] = 'js';
                    $result[$count]['src'] = $sDirFull;
                }
                //Thuc hien include file Css
                $filetypeinDirCss = @substr($arrTemp[$index], strlen($file) - 3, 3);
                $filetypeinDirCss = @strtolower($filetypeinDirCss);
                if ($filetype == "css" && $filetypeinDirCss == "css") {
                    $sDirFull = $sDir . "/" . $arrTemp[$index];
                    $result[$count]['type'] = 'css';
                    $result[$count]['src'] = $sDirFull;
                }
                $count++;
            }
        }

        return $result;
    }

    /**
     * Lấy các file có trong một thư mục trên server
     *
     * @return array
     */
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
            if (!file_exists($sPath)) {
                mkdir($sPath, 0777);
                $sPath = $sPath;
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

    public function _writeFile($spFilePath, $spContent)
    {
        if (file_exists($spFilePath)) {
            chmod($spFilePath, 0777);
        }
        $handle = fopen($spFilePath, "w+");
        if ($handle) {
            fwrite($handle, $spContent);
            fclose($handle);
        }
    }

    public static function _uploadFileList($sDir, $sAttachFileName = "FileName", $sDelimitor = "@!~!@")
    {
        $path = self::_createFolder($sDir, date('Y'), date('m'), date('d'));
        $sFileNameList = "";
        $names = $_FILES[$sAttachFileName]['name'];
        $i = 1;
        $return = array();
        foreach ($names as $name) {
            if ($name) {
                $url_path = "";
                $random = self::_get_randon_number();
                $sFullFileName = date("Y") . '_' . date("m") . '_' . date("d") . "_" . date("H") . date("i") . date("u") . $random . "!~!" . self::_replaceBadChar($name['file']);
                // Neu la file
                if (is_file($name['file']) || $name['file'] != "") {
                    //dd($path . self::_convertVNtoEN($sFullFileName));
                    $url_path = "storage/attach-file/" . date("Y") . '/' . date("m") . '/' . date("d") . "/" . self::_convertVNtoEN($sFullFileName);
                    move_uploaded_file($_FILES[$sAttachFileName]['tmp_name'][$i]['file'], $path . self::_convertVNtoEN($sFullFileName));
                    //Neu la file
                    $sFileNameList .= $sFullFileName . $sDelimitor;
                }
                $return[$i]['name'] = $name['file'];
                $return[$i]['url'] = url($url_path);
                $i++;
            }
        }

        return $return;
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

    /**
     * Thay các ký tự không được lưu trong xml
     * 
     * @param string $str
     * @return string
     */
    public static function _replaceSpecialCharXml($str)
    {
        // Thay ký tự '&' thành ___AND___
        $str = preg_replace('/&/', '___AND___', $str);
        return $str;
    }

    /**
     * Thay các ký tự đã replace thành ký tự gốc
     * 
     * @param string $str
     * @return string
     */
    public static function _replaceSpecialCharXmlRevert($str)
    {
        // Thay ký tự ___AND___ thành &
        $str = preg_replace('/___AND___/', '&', $str);
        return $str;
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
    //Congtv
    public static function _exportsql($arrParameter, $spname)
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
        // echo 'Exec ' . $spname . $sql; exit;
        return 'Exec ' . $spname . $sql;
    }

    public static function _getPathbyName($filename)
    {
        $arrFile = explode("_", $filename);
        $target_dir = public_path("attach-file") . "\\";
        $path = $target_dir . $arrFile[0] . "\\" . $arrFile[1] . "\\" . $arrFile[2] . "\\" . $filename;

        return $path;
    }

    /**
     * Get mime content from extension
     */
    public static function mimeContentType(string $ext): string
    {
        $mime_types = array(
            'js'    => 'application/javascript',
            'json'  => 'application/json',
            'xml'   => 'application/xml',
            'swf'   => 'application/x-shockwave-flash',
            'zip'   => 'application/zip',
            'rar'   => 'application/zip',
            'exe'   => 'application/x-msdownload',
            'msi'   => 'application/x-msdownload',
            'cab'   => 'application/vnd.ms-cab-compressed',
            'pdf'   => 'application/pdf',
            'PDF'   => 'application/pdf',
            'ai'    => 'application/postscript',
            'eps'   => 'application/postscript',
            'ps'    => 'application/postscript',
            'doc'   => 'application/msword',
            'rtf'   => 'application/rtf',
            'xls'   => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'xlsx'  => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'ppt'   => 'application/vnd.ms-powerpoint',
            'odt'   => 'application/vnd.oasis.opendocument.text',
            'ods'   => 'application/vnd.oasis.opendocument.spreadsheet',
            'docx'  => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'pptx'  => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'txt'   => 'text/plain',
            'htm'   => 'text/html',
            'html'  => 'text/html',
            'php'   => 'text/html',
            'css'   => 'text/css',
            'png'   => 'image/png',
            'jpe'   => 'image/jpeg',
            'jpeg'  => 'image/jpeg',
            'jpg'   => 'image/jpeg',
            'gif'   => 'image/gif',
            'bmp'   => 'image/bmp',
            'ico'   => 'image/vnd.microsoft.icon',
            'tiff'  => 'image/tiff',
            'tif'   => 'image/tiff',
            'svg'   => 'image/svg+xml',
            'svgz'  => 'image/svg+xml',
            'psd'   => 'image/vnd.adobe.photoshop',
            'mp3'   => 'audio/mpeg',
            'flv'   => 'video/x-flv',
            'qt'    => 'video/quicktime',
            'mov'   => 'video/quicktime',
            'mpeg'  => 'video/mpeg',
            'webm'  => 'video/webm',
            'wmv'   => 'video/x-ms-wmv',
            'wmx'   => 'video/x-ms-wmx',
            'wvx'   => 'video/x-ms-wvx',
            'avi'   => 'video/x-msvideo',
            'movie' => 'video/x-sgi-movie',
        );
        // 'rar' => 'application/x-rar-compressed',
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        } else {
            return 'application/octet-stream';
        }
    }

    public static function addSignedFileName(string $fileName): string
    {
        $ext = explode('.', $fileName)[1];
        $name = explode('.', $fileName)[0];
        if ($ext !== 'pdf') return $fileName;
        return $name . '.signed.' . $ext;
    }
}
