<?php

namespace Modules\Core\Ncl;

use DB;
use Modules\Api\Helpers\FileHelper;
use Modules\Api\Models\Admin\AdditionalRequirementModel;
use Modules\Api\Models\Admin\RecordTypeFileModel;
use Modules\Api\Models\Admin\FilesModel;
use Modules\Api\Models\RecordWithdrawModel;

/**
 * Thư viện dùng chung cho toàn dự án.
 *
 * @author test <skype: test1505>
 */
class FunctionHelper
{

    /**
     * Convert ngày từ múi giờ này sang múi giờ khác
     */
    public static function convertDateTimeZone(string $date, string $fromTimeZone = 'UTC', string $toTimeZone = 'Asia/Ho_Chi_Minh', $outputFormat = 'Y-m-d H:i:s'): string
    {
        $utcDateTime = new \DateTime($date, new \DateTimeZone($fromTimeZone));
        $utcDateTime->setTimezone(new \DateTimeZone($toTimeZone));

        return $utcDateTime->format($outputFormat);
    }

    /**
     * Idea: chuyen doi thoi gian dang chuoi yyyymmdd thanh chuoi dang dd/mm/yyyy
     *
     * @param $psYyymmdd: la thoi gian dang chuoi
     * @return Chuoi dang dd/mm/yyyy(De hien thi tren man hinh)
     */
    public static function _yyyymmddToDDmmyyyy($psYyymmdd)
    {
        if (is_null($psYyymmdd) || empty($psYyymmdd) || trim($psYyymmdd) == "" || $psYyymmdd == 1900)
            return "";
        return date("d/m/Y", strtotime($psYyymmdd));
    }

    public static function _yyyymmddToDDmmyyyyHis($psYyymmdd)
    {
        if (is_null($psYyymmdd) || empty($psYyymmdd) || trim($psYyymmdd) == "" || $psYyymmdd == 1900)
            return "";
        return date("d/m/Y H:i:s", strtotime($psYyymmdd));
    }

    public static function _yyyymmddToHisDDmmyyyy($psYyymmdd)
    {
        if (is_null($psYyymmdd) || empty($psYyymmdd) || trim($psYyymmdd) == "" || $psYyymmdd == 1900)
            return "";
        return date("H:i:s d/m/Y", strtotime($psYyymmdd));
    }

    public static function _yyyymmddToHHmm($psYyyymmdd)
    {
        if (is_null($psYyyymmdd) || trim($psYyyymmdd) == "")
            return "";
        return date("H:i", strtotime($psYyyymmdd));
    }

    /**
     * @Idea : chuyen doi ngay dang mm/dd/yyyy thanh ngay dang yyyy/mm/dd
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
     * Lấy html ngày hẹn và thông tin quá hạn, đến hạn
     * 
     * @param string $appointed
     * @return string
     */
    public static function _getHtmlOverdueDays($appointed, $status = '')
    {
        if (empty($appointed) || trim($appointed) == '') return '';
        $strDate = self::_yyyymmddToDDmmyyyy($appointed);
        $arrNotShow = array('MOI_TIEP_NHAN', 'BO_SUNG', 'CHUYEN_THUE');
        if ($status != '' && array_search($status, $arrNotShow) !== false) return $strDate;
        // Creates DateTime objects
        $datetime1 = date_create(date('Y-m-d'));
        $datetime2 = date_create(date('Y-m-d', strtotime($appointed)));
        // Calculates the difference between DateTime objects
        $interval = date_diff($datetime1, $datetime2);
        // Printing result in total day format
        $overdue = (int) $interval->format('%R%a');
        $html = '';
        if ($overdue < 0) {
            $html = '<br/><div style="background: #f44336;padding: 5px;border-radius: 20px;text-align: center;color: #fff;">Quá hạn</div>';
        } else if ($overdue == 0) {
            $html = '<br/><div style="background: #00a846;padding: 5px;border-radius: 20px;text-align: center;color: #fff;">Đến hạn</div>';
        } else if ($overdue <= 6) {
            $html = '<br/><div style="background: #eae222;padding: 5px;border-radius: 20px;text-align: center;">Còn ' . $overdue . ' ngày</div>';
        }

        return $strDate . $html;
    }

    public static function _convertAddRequirement($code)
    {
        $records = AdditionalRequirementModel::where('record_code', $code)->first();
        if (!$records) return 'Chờ bổ sung';
        if ($records->is_added === "1") {
            return '<div style="background: #00a846;padding: 5px;border-radius: 20px;color: #fff;text-align: center;">Đã bổ sung</div>';
        } elseif ($records->is_added === "0") {
            return '<div style="background: #eae222;padding: 5px;border-radius: 20px;text-align: center;">Chờ công dân bổ sung</div>';
        } else {
            return 'Chờ bổ sung';
        }
    }

    public static function _convertWithdraw($code)
    {
        $withdraw = RecordWithdrawModel::where('record_code', $code)->first();
        if ($withdraw->status === '0') {
            return 'Chờ phê duyệt';
        }
    }

    public static function _convertStatusAppraisal($code)
    {
        if ($code == 1) {
            return "Đã thẩm định";
        } else if ($code == 0) {
            return "Chưa thẩm định";
        }
    }
    public static function _countResultObject($id)
    {
        return DB::table("decision_object")->where("object_id", $id)->count();
    }
    public function _convertStatus($current_status)
    {
        switch ($current_status) {
            case 'MOI_TIEP_NHAN':
                $codeNcl = 'Mới tiếp nhận';
                break;
            case 'BO_SUNG':
                $codeNcl = 'Bổ sung';
                break;
            case 'DA_CHUYEN_LIEN_THONG':
                $codeNcl = 'Đã chuyển liên thông';
                break;
            case 'CAP_PHEP':
                $codeNcl = 'Cấp phép';
                break;
            case 'CHO_GUI_LIEN_THONG':
                $codeNcl = 'Liên thông chờ gửi';
                break;
            case 'THU_LY':
                $codeNcl = 'Thụ lý';
                break;
            case 'TRINH_KY':
                $codeNcl = 'Trình ký';
                break;
            case 'CHO_PHAN_CONG':
                $codeNcl = 'Chờ phân công';
                break;
            case 'DA_TRA_KQ':
                $codeNcl = 'Đã trả kết quả';
                break;
            case 'CHO_TRA_KET_QUA':
                $codeNcl = 'Chờ trả kết quả';
                break;
            case 'TRA_KET_QUA':
                $codeNcl = 'Trả kết quả';
                break;
            case 'CAP_PHEP_41':
                $codeNcl = 'Cấp phép';
                break;
            case 'DA_XOA':
                $codeNcl = 'Đã xóa';
                break;
            default:
                $codeNcl = '';
        }
        return $codeNcl;
    }

    public static function _xmlGetXmlTagValue($stringxml, $parenttag, $tagxml)
    {
        $objxmlStringdata = simplexml_load_string($stringxml);
        $return = "";
        if (!isset($objxmlStringdata->$parenttag)) return $return;
        $arrData = (array)$objxmlStringdata->$parenttag ?? [];
        if (!isset($arrData[$tagxml])) return $return;
        if (is_string($arrData[$tagxml]) && (string)$arrData[$tagxml] !== "") $return = $arrData[$tagxml];
        elseif (is_array($arrData[$tagxml])) {
            foreach ($arrData[$tagxml] as $key => $value) {
                if (is_string($value) && (string)$value !== '') {
                    $return = (string)$value;
                    break;
                }
            }
        }

        return Library::_replaceSpecialCharXmlRevert($return);
    }

    /**
     * Lấy các file đính kèm bao gồm cả trong XML và File server
     */
    public static function _getAttachFile(string $recordId, string $xml, string $parentTag, string $tagXml, string $ownerCode, string $ownerTransition = '')
    {
        $tlKhac = [];
        $fromServer = self::_getAttachFileServer($recordId, $xml);
        $fromXml = self::_xmlGetAttackFile($recordId, $xml, $parentTag, $tagXml, $ownerCode, $ownerTransition);
        foreach ($fromServer as $k => $a) {
            if ($a['code'] == 'TL_KHAC') {
                foreach ($a['file'] as $f) {
                    array_push($tlKhac, $f);
                }
                unset($fromServer[$k]);
            }
        }
        foreach ($fromXml as  $k => $a) {
            if ($a['code'] == 'TL_KHAC') {
                foreach ($a['file'] as $f) {
                    array_push($tlKhac, $f);
                }
                unset($fromXml[$k]);
            }
        }
        $return = array_merge($fromServer, $fromXml);
        if (count($tlKhac) > 0) {
            $otherFile = [
                'code' => 'TL_KHAC',
                'name' => 'Tài liệu khác',
                'number' => strlen(count($tlKhac)) < 2 ? '0' . count($tlKhac) : count($tlKhac),
                'file' => $tlKhac,
            ];
            array_push($return, $otherFile);
        }

        return $return;
    }

    /**
     * Lấy file server theo hồ sơ
     */
    public static function _getAttachFileServer(string $recordId, string $xml)
    {
        if ((string) $recordId === '' || (string) $xml === '') return [];
        $fileHelper = new FileHelper();
        $xml_data = simplexml_load_string($xml);
        $xml_data = json_decode(json_encode($xml_data), true);
        $tldt = $xml_data['data_list']['tai_lieu_dt'] ?? '';
        $arrFileStream = [];
        if (!empty($tldt)) {
            $arrTLDT = explode('#!~*!#', trim($tldt, '#!~*!#'));
            foreach ($arrTLDT as $value) {
                $arrValue = explode('|#|', $value);
                if (isset($arrValue[1]) && !empty($arrValue[1])) {
                    array_push($arrFileStream, $arrValue[1]);
                }
            }
        }
        $arrReturn = [];
        $arrTlKhac = [];
        $files = [];
        $i = 0;
        $filesTb = FilesModel::where('code_recordtypefile', '!=', 'FILE_XU_LY')->where(function ($sql) use ($arrFileStream, $recordId) {
            $sql->where('record_id', $recordId);
            if (!empty($arrFileStream)) {
                $sql->orWhereIn('filestream_id', $arrFileStream);
            }
        });
        $filesTb = $filesTb->get();
        foreach ($filesTb as $file) {
            // $arrFile = $fileHelper->getUrlFileServer($file->filestream_id, $file->file_name);
            $fname = explode('!~!', $file->file_name);
            $url = $fileHelper->url($file->file_name, $file->filestream_id);
            if ($file->code_recordtypefile === 'TL_KHAC') {
                if (empty($arrTlKhac)) {
                    $arrTlKhac = [
                        'code' => $file->code_recordtypefile,
                        'name' => $file->name_recordtypefile,
                        'number' => '01',
                    ];
                }
                array_push($files, [
                    'fileName' => end($fname),
                    'url' => $url,
                ]);
            }
            // set tài liệu thường
            else if (!empty($url)) {
                $arrReturn[$i]['code'] = $file->code_recordtypefile;
                $arrReturn[$i]['name'] = $file->name_recordtypefile;
                $arrReturn[$i]['number'] = '01';
                $arrReturn[$i]['fileName'] = end($fname);
                $arrReturn[$i]['url'] = $url;
                $i++;
            }
        }
        if (count($files) > 0) {
            $arrTlKhac['file'] = $files;
            $number = strlen(count($files)) < 2 ? '0' . count($files) : count($files);
            $arrTlKhac['number'] = $number;
        }
        if (count($arrTlKhac) > 0) $arrReturn[$i++] = $arrTlKhac;

        return $arrReturn;
    }

    public static function _xmlGetAttackFile($idRecord, $stringxml, $parenttag, $tagxml, $ownerCode, $ownerTransition = '')
    {
        $FileHelper       = new FileHelper();
        // $unit             = auth('sanctum')->user()->owner_code;
        // $registorId       = self::_xmlGetXmlTagValue($stringxml, $parenttag, 'registor_identification');
        $objxmlStringdata = simplexml_load_string($stringxml);
        $strFile          = (string) $objxmlStringdata->$parenttag->$tagxml;
        $arrFiles         = \explode("#!~*!#", $strFile);
        $i                = 0;
        $arrReturn        = array();
        $arrTlKhac        = array();
        $files            = array();
        if (sizeof($arrFiles) == 0) return $arrReturn;
        foreach ($arrFiles as $arrFile) {
            if ($arrFile === "") continue;
            $arrFileTemp = \explode("|#|", $arrFile);
            if (!isset($arrFileTemp[4])) continue;
            // $arrFile = $FileHelper->getUrlFileByName($arrFileTemp[4], 'attach-file', $unit, $registorId);
            $fname = explode('!~!', $arrFileTemp[4]);
            $fileName = end($fname);
            list($year) = explode('_', $arrFileTemp[4]);
            $url = $FileHelper->url($arrFileTemp[4], '', getOwnerCode($ownerCode), $year);
            // Nếu không có ở đơn vị hiện tại thì check ở đơn vị liên thông
            // if ($arrFile['url'] == '' && (string) $ownerTransition !== '') {
            if (($url == '' || $url === false) && $ownerTransition !== '') {
                // $arrFile = $FileHelper->getUrlFileByName($arrFileTemp[4], 'attach-file', $ownerTransition, $registorId);
                $url = $FileHelper->url($arrFileTemp[4], '', getOwnerCode($ownerTransition), $year);
            }
            // set tài liệu khác
            if ($arrFileTemp[0] === 'TL_KHAC') {
                if (!isset($arrTlKhac[0]['code'])) {
                    $arrTlKhac = [
                        'code'   => $arrFileTemp[0],
                        'name'   => $arrFileTemp[1],
                        'number' => '01',
                    ];
                }
                array_push($files, [
                    'fileName' => $fileName,
                    'url'      => $url,
                ]);
            }
            // set tài liệu thường
            else if (isset($fileName) && $fileName !== "") {
                $arrReturn[$i]['code'] = $arrFileTemp[0];
                $arrReturn[$i]['name'] = $arrFileTemp[1];
                $arrReturn[$i]['number'] = "0" . $arrFileTemp[2];
                $arrReturn[$i]['fileName'] = $fileName;
                $arrReturn[$i]['url'] = $url;
                $i++;
            }
        }
        if (count($files) > 0) {
            $arrTlKhac['file'] = $files;
            $number = strlen(count($files)) < 2 ? '0' . count($files) : count($files);
            $arrTlKhac['number'] = $number;
        }
        if (count($arrTlKhac) > 0) $arrReturn[$i++] = $arrTlKhac;

        return $arrReturn;
    }

    /**
     * Lấy url file dịch vụ công
     * 
     * @param string $idRecord
     * @param string $stringxml
     * @return array
     */
    public static function _xmlGetAttackFileNetRecord($idRecord, $stringxml)
    {
        $arrReturn = [];
        $i = 0;
        $FileHelper = new FileHelper();
        $objxmlStringdata = simplexml_load_string($stringxml);
        // File vật lý
        $strFile = (string) $objxmlStringdata->data_list->tai_lieu_kt;
        $arrFiles = \explode("#!~*!#", trim($strFile, '#!~*!#'));
        // File server
        $strFileFs = (string) $objxmlStringdata->data_list->tai_lieu_dt;
        $arrFilesFs = \explode("#!~*!#", trim($strFileFs, '#!~*!#'));

        // Xử lý file vật lý
        if (sizeof($arrFiles) > 0) {
            foreach ($arrFiles as $arrFile) {
                if ($arrFile == '') continue;
                $arrFileTemp = \explode("|#|", $arrFile);
                if (!isset($arrFileTemp[4])) continue;
                $arrFile = $FileHelper->getUrlFilePortal($arrFileTemp[4], $idRecord);
                if (empty($arrFile['fileName'])) continue;
                $arrReturn[$i]['code'] = $arrFileTemp[0];
                $arrReturn[$i]['name'] = $arrFileTemp[1];
                if ($arrFileTemp[1] == '') $arrReturn[$i]['name'] = $arrFileTemp[0];
                $arrReturn[$i]['number'] = '0' . $arrFileTemp[2];
                $arrReturn[$i]['fileName'] = $arrFile['fileName'];
                $arrReturn[$i]['url'] = $arrFile['url'];
                $i++;
            }
        }
        // Xử lý file server
        if (sizeof($arrFilesFs) > 0) {
            foreach ($arrFilesFs as $arrFile) {
                if ($arrFile == '') continue;
                $arrFileTemp = \explode("|#|", $arrFile);
                if (empty($arrFileTemp[1])) continue;
                $f = FilesModel::where('filestream_id', $arrFileTemp[1])->first();
                // $f = PortalFilesModel::where('filestream_id', $arrFileTemp[1])->first();
                if ($f) {
                    $name = explode('!~!', $f->file_name);
                    $recordtypeFile = RecordTypeFileModel::where('code', $arrFileTemp[0])->first();
                    $arrReturn[$i]['name'] = $recordtypeFile->name;
                    $arrReturn[$i]['number'] = "01";
                    $arrReturn[$i]['fileName'] = end($name);
                    // $arrReturn[$i]['url'] = url('') . '/view-file/' . $f->filestream_id;
                    $arrReturn[$i]['url'] = config('internal.portal.url_view_file', 'https://dichvucong.thainguyen.gov.vn/portal') . '/view-file/' . $f->filestream_id;
                    $i++;
                }
            }
        }

        return $arrReturn;
    }

    /**
     * Lấy danh sách file đính kèm từ tiến độ hồ sơ
     * [luatnc update 09/01/2023]
     * @param string $fileName Tên file đính kèm
     * @return array danh sách file đính kèm
     */
    public static function _getFileFromRecordWork($fileName)
    {
        $return = array();
        if ($fileName === "") return $return;
        $arrFiles = \explode("#!~*!#", $fileName);
        $i = 0;
        $FileHelper = new FileHelper();
        foreach ($arrFiles as $file) {
            // Nếu là uuid thì lấy trong filserver
            if (\Str::isUuid($file)) {
                $fileSv = FilesModel::where('filestream_id', $file)->first();
                if ($fileSv) {
                    $fname = explode('!~!', $fileSv->file_name);
                    // $return[$i] = $FileHelper->getUrlFileServer($fileSv[0]['filestream_id'], $fileSv[0]['file_name']);
                    $return[$i] = [
                        'url'      => $FileHelper->url($fileSv->file_name, $fileSv->filestream_id),
                        'fileName' => end($fname),
                    ];
                    $i++;
                }
            }
            // Nếu là string bình thường thì lấy file trong ổ cứng
            else {
                // $return[$i] = $FileHelper->getUrlFileByName($file);
                $fname = explode('!~!', $file);
                $return[$i] = [
                    'url'      => $FileHelper->url($file, '', getOwnerCode(auth('sanctum')->user()->owner_code)),
                    'fileName' => end($fname),
                ];
                $i++;
            }
        }


        return $return;
    }

    // lay ra ten quoc tich theo ma quoc tich/ma quoc gia
    public static function _getNameNationality($nationalityCode)
    {
        $nationalityName = DB::table('system_list')
            ->join('system_listtype', 'system_listtype.id', '=', 'system_list.system_listtype_id')
            ->select('system_list.name')
            ->whereIn('system_listtype.code', ['LLTP_DM_QUOC_TICH', 'DM_QUOC_TICH', 'LGSP_DM_QUOC_TICH', 'LGSP_DM_QUOC_GIA', 'DM_QUOC_GIA'])
            ->where('system_list.code', '=', $nationalityCode)->first();
        return $nationalityName != null ? $nationalityName->name : '';
    }

    // tra ve ngay theo dang ngay dd thang MM nam YYYY
    public function _getDateRegister($date)
    {
        $ngay = date('d', strtotime($date));
        $thang = date('m', strtotime($date));
        $nam = date('Y', strtotime($date));
        return 'ngày ' . $ngay . ' tháng ' . $thang . ' năm ' . $nam;
    }

    // lay ra ten dan toc theo ma dan toc
    public static function _getNameNation($nationCode)
    {
        $nationName = DB::table('system_list')
            ->join('system_listtype', 'system_listtype.id', '=', 'system_list.system_listtype_id')
            ->select('system_list.name')
            ->whereIn('system_listtype.code', ['DM_DAN_TOC', 'LLTP_DM_DAN_TOC', 'DM_DAN_TOC_TCTK', 'LGSP_DM_LOAI_GIAY_TO_TUY_THAN', 'LGSP_DM_DAN_TOC'])
            ->where('system_list.code', '=', $nationCode)->first();

        return $nationName != null ? $nationName->name : '';
    }

    // lay ra noi dang ky theo danh muc LGSP
    public static function _getRegisterPlace($placeCode)
    {
        $placeName = DB::table('system_list')
            ->join('system_listtype', 'system_listtype.id', '=', 'system_list.system_listtype_id')
            ->select('system_list.name')
            ->whereIn('system_listtype.code', ['LGSP_DM_NOI_DANG_KY'])
            ->where('system_list.code', '=', $placeCode)->first();
        return $placeName != null ? $placeName->name : '';
    }

    // lay ra loai giay to tuy than theo danh muc LGSP
    public static function _getIdentificationType($itemCode)
    {
        $item = DB::table('system_list')
            ->join('system_listtype', 'system_listtype.id', '=', 'system_list.system_listtype_id')
            ->select('system_list.name')
            ->whereIn('system_listtype.code', ['LGSP_DM_LOAI_GIAY_TO_TUY_THAN'])
            ->where('system_list.code', '=', $itemCode)->first();
        return $item != null ? $item->name : '';
    }

    // lay ra ten loai trich luc ho tich
    public static function _getLoaiTrichLucHoTich($code)
    {
        $trichLucName = DB::table('system_list')
            ->join('system_listtype', 'system_listtype.id', '=', 'system_list.system_listtype_id')
            ->select('system_list.name')
            ->whereIn('system_listtype.code', ['LOAI_TRICH_LUC_HO_TICH'])
            ->where('system_list.code', '=', $code)->first();
        return $trichLucName != null ? $trichLucName->name : '';
    }

    // lay ra ten trong danh muc dia danh hanh chinh
    public static function _getDiaDanhHanhChinh($code)
    {
        $item = DB::table('system_list')
            ->join('system_listtype', 'system_listtype.id', '=', 'system_list.system_listtype_id')
            ->select('system_list.name')
            ->whereIn('system_listtype.code', ['DM_DIA_DANH_HANH_CHINH'])
            ->where('system_list.code', '=', $code)->first();
        return $item != null ? $item->name : '';
    }

    // tra ve gioi tinh theo ma gioi tinh
    public static function _getNameSex($sexCode)
    {
        switch ($sexCode) {
            case '1':
                return 'Nam';
                break;
            case '2':
                return 'Nữ';
                break;
            case '3':
                return 'Chưa xác định';
                break;
            default:
                return '';
                break;
        }
    }

    public static function _convertPhuongThucNhanKQ($type = '')
    {
        if ($type == '1') return 'Tại nhà thông qua dịch vụ bưu chính công ích';
        return 'Trực tiếp tại Bộ phận TN&TKQ';
    }

    /**
     * Chuyển sang số la mã
     * 
     */
    public static function romanic_number($integer, $upcase = true)
    {
        $table = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $return = '';
        while ($integer > 0) {
            foreach ($table as $rom => $arb) {
                if ($integer >= $arb) {
                    $integer -= $arb;
                    $return .= $rom;
                    break;
                }
            }
        }

        return $return;
    }

    // lay ten to chuc ca nhan trong xml
    public static function getObjectName($xml)
    {
        return self::_xmlGetXmlTagValue($xml, "data_list", "object_name");
    }

    // lay ten to chuc ca nhan trong xml
    public static function getObjectAddress($xml)
    {
        return self::_xmlGetXmlTagValue($xml, "data_list", "object_address");
    }

    /**
     * Lấy hình thức trả kết quả
     * [luatnc 08/09/2022]
     */
    public static function getTypeResult($typeResult, $currentStatus = null)
    {
        switch ($typeResult) {
            case 'CAP_PHEP':
                return ' (Cấp phép)';
            case 'TU_CHOI':
                return ' (Từ chối)';
            case 'RUT_HO_SO':
                return ' (Rút hồ sơ)';
            case 'DUNG_XU_LY':
                return ' (Dừng xử lý)';
            default:
                return '';
        }
    }

    public static function convert_number_to_words($number)
    {
        $hyphen      = ' ';
        $conjunction = ' ';
        $separator   = ' ';
        $negative    = 'âm ';
        $decimal     = ' phẩy ';
        $one         = 'mốt';
        $ten         = 'lẻ';
        $dictionary  = array(
            0                   => 'Không',
            1                   => 'Một',
            2                   => 'Hai',
            3                   => 'Ba',
            4                   => 'Bốn',
            5                   => 'Năm',
            6                   => 'Sáu',
            7                   => 'Bảy',
            8                   => 'Tám',
            9                   => 'Chín',
            10                  => 'Mười',
            11                  => 'Mười một',
            12                  => 'Mười hai',
            13                  => 'Mười ba',
            14                  => 'Mười bốn',
            15                  => 'Mười lăm',
            16                  => 'Mười sáu',
            17                  => 'Mười bảy',
            18                  => 'Mười tám',
            19                  => 'Mười chín',
            20                  => 'Hai mươi',
            30                  => 'Ba mươi',
            40                  => 'Bốn mươi',
            50                  => 'Năm mươi',
            60                  => 'Sáu mươi',
            70                  => 'Bảy mươi',
            80                  => 'Tám mươi',
            90                  => 'Chín mươi',
            100                 => 'trăm',
            1000                => 'ngàn',
            1000000             => 'triệu',
            1000000000          => 'tỷ',
            1000000000000       => 'nghìn tỷ',
            1000000000000000    => 'ngàn triệu triệu',
            1000000000000000000 => 'tỷ tỷ'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if ($number < 0) {
            return $negative . self::convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= strtolower($hyphen . ($units == 1 ? $one : $dictionary[$units]));
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= strtolower($conjunction . ($remainder < 10 ? $ten . $hyphen : null) . self::convert_number_to_words($remainder));
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number - ($numBaseUnits * $baseUnit);
                $string = self::convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= strtolower($remainder < 100 ? $conjunction : $separator);
                    $string .= strtolower(self::convert_number_to_words($remainder));
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }

    public static function percentDVC4($column, $iteration)
    {
        return '=(C' . $iteration . '/E' . $iteration . ')*100';
    }
}
