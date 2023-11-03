<?php

namespace Modules\Core\Ncl\PrintDynamic;

use Illuminate\Support\Str;
use DNS1D;
use Modules\Api\Models\Admin\PerMisionGroupModel;
use Modules\Api\Models\Admin\PermisionUserCateModel;
use Modules\Api\Models\Admin\UnitModel;
use Modules\Api\Models\Admin\UserModel;
use Modules\Api\Models\Admin\FilesModel;
use Modules\Api\Models\Admin\RecordTypeFileModel;
use Modules\Api\Models\RecordModel;
use Modules\Api\Models\RecordWorkModel;
use Modules\Core\Ncl\Exceptions\ResponseExeption;
use Storage;
use Modules\Core\Ncl\FormDynamic\listForm\QueryBuilder;
use Modules\Core\Ncl\FunctionHelper;
use Modules\Core\Ncl\Library;

class PrintDynamic
{

    protected $xmlData;
    protected $xmlforms;
    protected $pathXml;
    protected $templateProcessor;

    /**
     * Đường dẫn file temp vật lý
     */
    protected $fileTemplate;

    /**
     * Định dạng file xuất ra. pdf; docx
     */
    protected $typeExport = 'pdf';

    /**
     *  Dữ liệu data thay thế vào file temp.
     *  * Key: tên cần thay thế trong file temp. vd: ${test}
     *  * Value: Dữ liệu thay thế
     */
    protected $dataReplace;

    /**
     * Đường dẫn vật lý xuất file
     */
    protected $nameExport = 'temp_export.docx';

    protected $username = 'apiebhxhtool';
    protected $password = 'Nclvn@eBhXhToOl';

    public function setXmlData($xmlPath)
    {
        $this->xmlData = $this->getContentXml($xmlPath);
    }

    public function getXmlData()
    {
        return $this->xmlData;
    }

    public function getContentXml($xmlPath)
    {
        if (!file_exists($xmlPath)) {
            $xmlPath = base_path($xmlPath);
        }
        $stringxmlcontent = file_get_contents($xmlPath);

        return simplexml_load_string($stringxmlcontent);
    }

    public function convertXmlToArray($xmlstring)
    {
        if ($xmlstring) {
            $json = json_encode($xmlstring);
            $returnArray = json_decode($json, true);
        } else {
            $returnArray = false;
        }

        return $returnArray;
    }

    public function getXmlDataToArray($xmlstring)
    {
        $xml = simplexml_load_string($xmlstring);
        $json = json_encode($xml->data_list);
        $array = json_decode($json, TRUE);

        return $array;
    }

    public function getRecord($filters)
    {
        $QueryBuilder = new QueryBuilder();
        $xmlSqls = (array)$this->getXmlData()->list_of_object->list_sql;
        if (!isset($xmlSqls['type'])) {
            return false;
        }
        $QueryBuilder->init($xmlSqls, $filters, false);

        return $QueryBuilder->getData();
    }

    public function setFileTemplate($fileTemp)
    {
        $fileTempDoc =  $fileTemp . '.docx';
        $fileTempExcel =  $fileTemp . '.xlsx';
        $fileTempHtm =  $fileTemp . '.htm';
        if (file_exists($fileTempHtm) && $this->typeExport == 'html') {
            $this->fileTemplate = $fileTempHtm;
            return $this;
        }
        if (file_exists($fileTempDoc)) {
            $this->fileTemplate = $fileTempDoc;
            return $this;
        }

        if (!file_exists($fileTempExcel))
            throw new ResponseExeption("fileTemp: $fileTemp không tồn tại");
        $this->fileTemplate = $fileTempExcel;

        return $this;
    }

    public function setDataReplace($dataSql, $data)
    {
        $this->dataReplace['data'] = array_merge((array)$dataSql, (array)$data);
        return $this;
    }

    public function setTableDataReplace($dataReplace)
    {
        $this->dataReplace['table']['data'] = $dataReplace;
        return $this;
    }

    public function setNameExport($nameExport)
    {
        $this->nameExport = $nameExport  . '_' . date('ymdhis');
        return $this;
    }

    public function setTypeExport($type)
    {
        $this->typeExport = $type;
        return $this;
    }

    public function setDataReplaceNew($param, $dataSql = [])
    {
        $arrReplaceSpecialCharXmlRevert = ['recordtype_name'];
        $data = array();
        $dataTable = array();
        $dataList = array();
        $arrVariableReplace = $this->convertXmlToArray($this->getXmlData()->list_of_object->replace_list)['replace'];
        $arrVariableReplaceTable = array();
        $arrVariableReplaceList = array();
        $xml_data = array_key_exists('xml_data', $dataSql) ? $this->getXmlDataToArray($dataSql['xml_data']) : [];
        $xml_data = array_map(fn ($v) => Library::_replaceSpecialCharXmlRevert($v), $xml_data);
        $unitInfo = UnitModel::where('code', $param['owner_code'])->first();
        $userInfo = UserModel::find($param['userId']);
        foreach ($arrReplaceSpecialCharXmlRevert as $fieldRevert) {
            if (isset($dataSql[$fieldRevert])) $dataSql[$fieldRevert] = Library::_replaceSpecialCharXmlRevert($dataSql[$fieldRevert]);
        }
        foreach ($arrVariableReplace as $key => $variableReplace) {
            $data[$variableReplace['template_variable']] = '';
            //kiểm tra xem có trường data của table không
            if (array_key_exists('data_of_table', $variableReplace) && $variableReplace['data_of_table'] == 'true') {
                unset($data[$variableReplace['template_variable']]);
                array_push($arrVariableReplaceTable, $variableReplace);
                continue;
            }
            //kiểm tra xem có trường data của danh sách (list)
            if (array_key_exists('data_of_list', $variableReplace) && $variableReplace['data_of_list'] == 'true') {
                unset($data[$variableReplace['template_variable']]);
                array_push($arrVariableReplaceList, $variableReplace);
                continue;
            }
            //trường hợp data trong cột xml_data
            if ($variableReplace['from_xml_data'] == 'true') {
                $value = '';
                if (array_key_exists($variableReplace['field_name'], $xml_data)) {
                    $phpfunction = $variableReplace['phpfunction'] ?? null;
                    $value = empty($phpfunction) ?
                        $xml_data[$variableReplace['field_name']] :
                        FunctionHelper::$phpfunction($xml_data[$variableReplace['field_name']]);
                    if (strpos($variableReplace['template_variable'], 'FOR_') === 0 && is_string($value)) {
                        $value = 'cho ' . $value;
                    }
                }
                if (empty($value) && !empty($variableReplace['value_default'])) {
                    $value = $variableReplace['value_default'];
                }
                $data[$variableReplace['template_variable']] = $value;
                continue;
            }
            // trường hợp data truyền từ client cho mẫu in
            if (array_key_exists($variableReplace['field_name'], $param)) {
                $data[$variableReplace['template_variable']] = $param[$variableReplace['field_name']];
                continue;
            }
            // trường hợp data là một trường trong record
            if (array_key_exists($variableReplace['field_name'], $dataSql)) {
                $phpfunction = $variableReplace['phpfunction'] ?? null;
                $value = empty($phpfunction) ?
                    $dataSql[$variableReplace['field_name']] :
                    FunctionHelper::$phpfunction($dataSql[$variableReplace['field_name']]);
                if ($variableReplace['template_variable'] == 'RECEIVED_DATE' || $variableReplace['template_variable'] == 'APPOINTED_DATE') {
                    $arrDate = explode(' ', $value);
                    if (isset($arrDate[1])) {
                        $value = $arrDate[0] . ' ngày ' . $arrDate[1];
                        // $value = date('H:i:s \n\g\à\y d/m/Y', strtotime($data[$variableReplace['template_variable']]));
                    }
                }
                if (empty($value) && !empty($variableReplace['value_default'])) {
                    $value = $variableReplace['value_default'];
                }
                $data[$variableReplace['template_variable']] = $value;
                continue;
            }

            switch ($variableReplace['field_name']) {
                case 'TYPE_FOR_REGISTOR_NAME': // nộp hồ sơ cho doanh nghiệp ( nội dung ... nộp hồ sơ cho danh nghiệp ...)
                    $data[$variableReplace['template_variable']] = isset($xml_data['registor_name']) ? $xml_data['registor_name'] : '';
                    break;
                case 'FEE': // lấy lệ phí doanh nghiệp
                    $data[$variableReplace['template_variable']] = !empty($dataSql['fee']) ? $dataSql['fee'] . ' (VND)' : '';
                    break;
                case 'OBJECT_ADDRESS': // lấy đại chỉ doanh nghiệp
                    $data[$variableReplace['template_variable']] = isset($xml_data['object_address']) ? $xml_data['object_address'] : '';
                    break;
                case 'CODE_TAX': // lấy mã số thuế doanh nghiệp
                    $data[$variableReplace['template_variable']] = isset($xml_data['registor_identification']) ? $xml_data['registor_identification'] : '';
                    break;
                case 'CURRENT_MINUTE': // lay ngay hien tai
                    $data[$variableReplace['template_variable']] = date('i');
                    break;
                case 'CURRENT_HOUR': // lay ngay hien tai
                    $data[$variableReplace['template_variable']] = date('H');
                    break;
                case 'CURRENT_DATE': // lay ngay hien tai
                    $data[$variableReplace['template_variable']] = date('d');
                    break;
                case 'CURRENT_MONTH': // lay thang hien tai
                    $data[$variableReplace['template_variable']] = date('m');
                    break;
                case 'CURRENT_YEAR': // lay nam hien tai
                    $data[$variableReplace['template_variable']] = date('Y');
                    break;
                case 'GET_DATE': // lay ngay/thang/nam hien tai
                    $data[$variableReplace['template_variable']] = 'ngày ' . date('d') . ' tháng ' . date('m') . ' năm ' . date('Y'); //dung ga
                    break;
                case 'PROCESS_NUMBER_DATE': //lay ra so ngay xu ly
                    $processNumberDate = array_key_exists('process_date', $xml_data) ? $xml_data['process_date'] : $dataSql['process_number_date'];
                    if (empty($processNumberDate)) $processNumberDate = 'trong';
                    $data[$variableReplace['template_variable']] = $dataSql['is_not_apointed_date'] == '1' ? 'Không quy định' : $processNumberDate;
                    break;
                case 'STAFF_NAME': //lay ten cua tai khoan dang nhap in
                    $data[$variableReplace['template_variable']] = $userInfo->name;
                    break;
                case 'STAFF_PHONE': //lay so dt của user in
                    $data[$variableReplace['template_variable']] = $userInfo->mobile;
                    break;
                case 'STAFF_UNIT_NAME':  //lay ten co quan cua tai khoan in
                    $data[$variableReplace['template_variable']] = $unitInfo->name;
                    break;
                case 'PRINT_PLACE':  //lay noi in
                    if ($unitInfo->type_group == 'SO_NGANH' || $unitInfo->parentName->type_group == 'SO_NGANH') {
                        $name = 'Thái Nguyên';
                    } else if ($unitInfo->type_group == 'PHUONG_XA' || $unitInfo->type_group == 'QUAN_HUYEN') {
                        $name = $unitInfo->name;
                    } else {
                        $name = $unitInfo->parentName->name;
                    }
                    $data[$variableReplace['template_variable']] = trim($name);
                    break;
                case 'OWNER_NAME_UPPERCASE': //lay co quan in phieu viet hoa
                    $data[$variableReplace['template_variable']] = Str::upper($unitInfo->name);
                case 'DISTRICT_NAME': //lay co quan in phieu viet hoa
                    $data[$variableReplace['template_variable']] = $unitInfo->type_group == 'PHUONG_XA' ? $unitInfo->parentName->name : $unitInfo->name;
                    break;
                case 'OWNER_NAME_TITLE': // Tên cơ quan góc trên bên trái
                    $name = 'TỈNH THÁI NGUYÊN';
                    if ($unitInfo->type_group == 'PHUONG_XA' || $unitInfo->type_group == 'QUAN_HUYEN') {
                        $name = Str::upper($unitInfo->name);
                    } else {
                        $conf = config('moduleInitConfig.district');
                        foreach ($conf as $c) {
                            if ($c['code_branch'] == $unitInfo->code) {
                                $unitParent = UnitModel::where('code', $c['code'])->first();
                                $name = Str::upper($unitParent->name);
                                break;
                            }
                        }
                    }
                    $data[$variableReplace['template_variable']] = $name;
                    break;
                case 'OWNER_NAME': //lay co quan in phieu them UY BAN NHAN DAN (neu khong phai so nganh)
                    if ($unitInfo->type_group == 'SO_NGANH') {
                        $name = $unitInfo->name;
                    } else {
                        $name = $unitInfo->name;
                        $conf = config('moduleInitConfig.district');
                        foreach ($conf as $c) {
                            if ($c['code_branch'] == $unitInfo->code) {
                                $unitParent = UnitModel::where('code', $c['code'])->first();
                                $name = $unitParent->name;
                                if ($unitParent->type_group != 'SO_NGANH') $name  = $name;
                            }
                        }
                    }
                    $data[$variableReplace['template_variable']] = $name;
                    break;
                case 'OWNER_NAME_UPPER': //lay co quan in phieu them UY BAN NHAN DAN (neu khong phai so nganh)
                    if ($unitInfo->type_group == 'SO_NGANH') {
                        $name = Str::upper($unitInfo->name);
                    } else {
                        $name = Str::upper($unitInfo->name);
                        $conf = config('moduleInitConfig.district');
                        foreach ($conf as $c) {
                            if ($c['code_branch'] == $unitInfo->code) {
                                $unitParent = UnitModel::where('code', $c['code'])->first();
                                $name = Str::upper($unitParent->name);
                                if ($unitParent->type_group != 'SO_NGANH') $name  = $name;
                            }
                        }
                    }
                    $data[$variableReplace['template_variable']] = $name;
                    break;
                case 'BO_PHAN_TIEP_NHAN':
                    $str = 'Trung tâm Phục vụ hành chính công';
                    if ($unitInfo->type_group == 'PHUONG_XA' || $unitInfo->type_group == 'QUAN_HUYEN' || $unitInfo->parentName->type_group == 'QUAN_HUYEN') {
                        $str = 'Bộ phận Tiếp nhận và Trả kết quả';
                    } else {
                        $conf = config('moduleInitConfig.district');
                        foreach ($conf as $c) {
                            if ($c['code_branch'] == $unitInfo->code) {
                                $str = 'Bộ phận Tiếp nhận và Trả kết quả';
                                break;
                            }
                        }
                    }
                    $data[$variableReplace['template_variable']] = $str;
                    break;
                case 'BO_PHAN_TIEP_NHAN_UPPER':
                    $str = 'Trung tâm Phục vụ hành chính công';
                    if ($unitInfo->type_group == 'PHUONG_XA' || $unitInfo->type_group == 'QUAN_HUYEN' || $unitInfo->parentName->type_group == 'QUAN_HUYEN') {
                        $str = 'Bộ phận Tiếp nhận và Trả kết quả';
                    } else {
                        $conf = config('moduleInitConfig.district');
                        foreach ($conf as $c) {
                            if ($c['code_branch'] == $unitInfo->code) {
                                $str = 'Bộ phận Tiếp nhận và Trả kết quả';
                                break;
                            }
                        }
                    }
                    $data[$variableReplace['template_variable']] = Str::upper($str);
                    break;
                case 'BPTN':
                    $str = 'TTPVHCC';
                    if ($unitInfo->type_group == 'PHUONG_XA' || $unitInfo->type_group == 'QUAN_HUYEN' || $unitInfo->parentName->type_group == 'QUAN_HUYEN') {
                        $str = 'BPTNTKQ';
                    } else {
                        $conf = config('moduleInitConfig.district');
                        foreach ($conf as $c) {
                            if ($c['code_branch'] == $unitInfo->code) {
                                $str = 'BPTNTKQ';
                                break;
                            }
                        }
                    }
                    $data[$variableReplace['template_variable']] = Str::upper($str);
                    break;
                case 'DISTRICT_NAME': //lay ten quan huyen cua co quan tai khoan dang lam viec
                    $districtName = $unitInfo->type_group == 'QUAN_HUYEN' ? $unitInfo->name : $unitInfo->parentName->name;
                    $data[$variableReplace['template_variable']] = $districtName;
                    break;
                case 'MONEY':
                    $data[$variableReplace['template_variable']] = $param['money'];
                    break;
                case 'RECEIVE_RESULT_PLACE':
                    $receviceResultPlace = $dataSql['return_result_place']  == 1 ? 'Dịch vụ bưu chính công ích' : 'Bộ phận Tiếp nhận và Trả kết quả';
                    if ($unitInfo->type_group == 'PHUONG_XA' || $unitInfo->type_group == 'QUAN_HUYEN' || $unitInfo->parentName->type_group == 'QUAN_HUYEN') {
                        // 
                    } else {
                        if ($receviceResultPlace == 'Bộ phận Tiếp nhận và Trả kết quả' && ($unitInfo->type_group == 'SO_NGANH' || $unitInfo->parentName->type_group == 'SO_NGANH')) {
                            $receviceResultPlace = 'Trung tâm Phục vụ hành chính công';
                        }
                        $conf = config('moduleInitConfig.district');
                        foreach ($conf as $c) {
                            if ($c['code_branch'] == $unitInfo->code && $receviceResultPlace == 'Trung tâm Phục vụ hành chính công') {
                                $receviceResultPlace = 'Bộ phận Tiếp nhận và Trả kết quả';
                                break;
                            }
                        }
                    }
                    $data[$variableReplace['template_variable']] = $receviceResultPlace;
                    break;
                case 'RETURN_RESULT_ADDRESS':
                    $returnResultAddress = isset($xml_data['return_result_address']) && $dataSql['return_result_place'] == 1 ? 'Địa chỉ nhận kết quả: ' . $xml_data['return_result_address'] : '';
                    $data[$variableReplace['template_variable']] = $returnResultAddress;
                    break;
                case 'NAME_MONEY':
                    $phpfunction = $variableReplace['phpfunction'] ?? null;
                    $money = str_replace(',', '', $param['money']);
                    $data[$variableReplace['template_variable']] = FunctionHelper::$phpfunction($money) . ' đồng';
                    break;
                case 'RESULT_DATE':
                    $data[$variableReplace['template_variable']] = date('d') . '/' . date('m') . '/' . date('Y');
                    break;
                case 'DEPARTMENT_NAMES': //lay co quan cho phieu kiem soat
                    $ward = UnitModel::where('code', $param['unitCode'])->where('type_group', 'PHUONG_XA')->pluck('name')->toArray();
                    if (!empty($ward)) {
                        $data['DEPARTMENT_NAMES'] = implode(',', $ward);
                    } else {
                        $permission_group_handle  = PerMisionGroupModel::where('code', 'CAN_BO_THU_LY')->first();
                        $record  = RecordModel::where('id', $param['idRecord'])->first();
                        $cate_record = $record->recordtype->cate;
                        $permission_cate = PermisionUserCateModel::select('unit_id')->where('cate', $cate_record)
                            ->where('permission_group_code', $permission_group_handle->id)
                            ->where('owner_code', $param['owner_code'])
                            ->groupBy('unit_id')->pluck('unit_id');

                        $department = UnitModel::where(function ($query) {
                            $query->whereNull('type_group')
                                ->orwhere('type_group', '');
                        })->pluck('name')->toArray();

                        $data[$variableReplace['template_variable']] = implode(',', $department);
                    }
                    break;
                case 'USER_RECEIVE':
                    $recordWork = RecordWorkModel::select('user_name')
                        ->where('work_type', 'THU_LY')
                        ->where('record_id', $dataSql['id'])->first();
                    $userReceive = $recordWork != null ? substr($recordWork->user_name, strripos($recordWork->user_name, '-', 1) + 1) : ''; // +1 là để xóa dấu -
                    $data[$variableReplace['template_variable']] =  $userReceive;
                    break;
                case 'HANDLE_DEPARTMENT': //lay co quan in phieu
                    $department = UnitModel::where('id', $param['unitId'])->first();
                    $data[$variableReplace['template_variable']] = $department->name;
                    break;
                case 'BARCODE': //barcode
                    unset($data[$variableReplace['template_variable']]);
                    $codeinbar = $dataSql['record_code'];
                    $codeinbar = str_replace('000.', '', $codeinbar);
                    $codeinbar = str_replace('.H55', '', $codeinbar);
                    $codeinbar = str_replace('.', '', $codeinbar);
                    $codeinbar = str_replace('-', '', $codeinbar);
                    $barcodeImage = $this->getBarcode($codeinbar);
                    $this->dataReplace['image']['BARCODE'] = $barcodeImage;
                    break;
                case 'OR_PHONE_CONTACT': // Chi nhánh văn phòng đăng ký đất đai
                    $str = '';
                    $conf = config('moduleInitConfig.district');
                    foreach ($conf as $c) {
                        if ($c['code_branch'] == $unitInfo->code && trim((string)$unitInfo->mobile) != '') {
                            $str = 'hoặc qua số điện thoại: ' . $unitInfo->mobile;
                            break;
                        }
                    }
                    $data[$variableReplace['template_variable']] = $str;
                default:
                    break;
            }
        }

        // Lấy các file thành phần
        $recordtypeFiles = RecordTypeFileModel::where('ecs_recordtype_id', $dataSql['recordtype_id'])->orderBy('order')->get()->toArray();
        // Mảng tài liệu được lưu vào fileserver
        $fileServers = FilesModel::where('record_id', $param['idRecord'])->get();
        $arrFileServer = $fileServers->isNotEmpty() ? $fileServers->toArray() : [];
        // Lấy các tài liệu đính kèm để hiển thị dạng BẢNG
        foreach ($arrVariableReplaceTable as $key => $variableReplaceTable) {
            $dt = $this->genArrFilePrint($variableReplaceTable, $xml_data, $recordtypeFiles, $arrFileServer);
            $dataTable[$variableReplaceTable['template_variable']] = $dt;
        }
        // Lấy các tài liệu đính kèm để hiển thị dạng DANH SÁCH
        foreach ($arrVariableReplaceList as $key => $variableReplaceList) {
            $dt = $this->genArrFilePrint($variableReplaceList, $xml_data, $recordtypeFiles, $arrFileServer);
            $dataList[$variableReplaceList['template_variable']] = $dt;
        }

        $this->dataReplace['data'] = $data;
        $this->dataReplace['table']['data'] = $dataTable;
        $this->dataReplace['list']['data'] = $dataList;

        return $this;
    }

    /**
     * Lấy mảng dữ liệu tài liệu kèm theo để in
     * 
     * @param array $variableReplace Thông tin cấu hình trong xml
     * @param array $xmlData Mảng dữ liệu xml
     * @param array $recordtypeFiles Mảng các thành phần theo TTHC
     * @param array $arrFileServer Mảng các file server theo hồ sơ
     * @return array
     */
    private function genArrFilePrint($variableReplace, $xmlData, $recordtypeFiles, $arrFileServer)
    {
        $stt          = 0;
        $totalTlKhac  = 0;
        $arrFilePrint = array();
        // Mảng tài liệu được lưu trong XML (file vật lý)
        $arrFileLocal = $this->genArrFileLocal($variableReplace, $xmlData);
        if (count($arrFileServer) > 0) {
            foreach ($arrFileLocal as $k => $v) {
                $key = array_search($v['code'], array_column($arrFileServer, 'code_recordtypefile'));
                if ($key !== false) unset($arrFileLocal[$k]);
            }
        }
        $arrFileLocal = array_values($arrFileLocal);
        // Lấy file theo thứ tự recordtype_file
        foreach ($recordtypeFiles as $k => $rtf) {
            if (count($arrFileServer) > 0) {
                $this->pushToFilePrint($arrFilePrint, $stt, $variableReplace, $rtf, $arrFileServer, 'code_recordtypefile', 'name_recordtypefile');
            }
            if (count($arrFileLocal) > 0) {
                $this->pushToFilePrint($arrFilePrint, $stt, $variableReplace, $rtf, $arrFileLocal);
            }
        }
        // Nếu có tl khác
        array_map(function ($v) use (&$totalTlKhac) {
            if ($v['code'] == 'TL_KHAC') $totalTlKhac++;
        }, $arrFileLocal);
        array_map(function ($v) use (&$totalTlKhac) {
            if ($v['code_recordtypefile'] == 'TL_KHAC') $totalTlKhac++;
        }, $arrFileServer);
        if ($totalTlKhac > 0) {
            $stt++;
            array_push($arrFilePrint, [
                $variableReplace['variable_column'][0] => $stt,
                $variableReplace['variable_column'][1] => 'Tài liệu khác',
                $variableReplace['variable_column'][2] => $totalTlKhac,
            ]);
        }

        return $arrFilePrint;
    }

    /**
     * Tạo mảng các file vật lý từ xml record
     * 
     * @param array $variableReplace Thông tin cấu hình trong xml
     * @param array $xmlData Mảng dữ liệu xml
     * @return array
     */
    private function genArrFileLocal($variableReplace, $xmlData)
    {
        $arrFileLocal = array();
        if ($variableReplace['field_name'] == 'tai_lieu_kt' && !empty($xmlData['tai_lieu_kt'])) {
            $arrStrTlkt = explode('#!~*!#', $xmlData['tai_lieu_kt']);
            foreach ($arrStrTlkt as $tlkt) {
                if ((string)$tlkt == '') continue;
                $e = explode('|#|', $tlkt);
                $item = [
                    'code' => $e[0],
                    'name' => $e[1],
                ];
                array_push($arrFileLocal, $item);
            }
        }
        // if (isset($variableReplace['field_name2']) && $variableReplace['field_name2'] == 'tl_khac' && !empty($xmlData['tl_khac'])) {
        //     $arrStrTlkt = explode('#!~*!#', $xmlData['tl_khac']);
        //     foreach ($arrStrTlkt as $tlkt) {
        //         if ((string)$tlkt == '') continue;
        //         $e = explode('|#|', $tlkt);
        //         $item = [
        //             'code' => $e[0],
        //             'name' => $e[1],
        //         ];
        //         array_push($arrFileLocal, $item);
        //     }
        // }

        return $arrFileLocal;
    }

    /**
     * Đẩy vào mảng File Print để in
     * 
     * @param array &$arrFilePrint Mảng để in
     * @param int &$stt Số thứ tự
     * @param array $variableReplace Thông tin cấu hình trong xml
     * @param array $recordtypeFile Thông tin của một thành phần cụ thể trong recordtype_file
     * @param array $arrFile Mảng File vật lý hoặc File server
     * @param string $keyCode key name để lấy mã tài liệu trong $arrFile
     * @param string $keyName key name để lấy tên tài liệu trong $arrFile
     * @return void
     */
    private function pushToFilePrint(&$arrFilePrint, &$stt, $variableReplace, $recordtypeFile, $arrFile, $keyCode = 'code', $keyName = 'name')
    {
        $key = array_search($recordtypeFile['code'], array_column($arrFile, $keyCode));
        if ($key !== false) {
            $stt++;
            $temp[$variableReplace['variable_column'][0]] = $stt;
            $temp[$variableReplace['variable_column'][1]] = $arrFile[$key][$keyName];
            $temp[$variableReplace['variable_column'][2]] = '01';
            array_push($arrFilePrint, $temp);
        }
    }

    public function setDataReplaceBanGiao($param, $dataSql = [])
    {
        $data = array();
        $dataTable = array();
        $arrVariableReplaceTable = array();

        $arrVariableReplace = $this->convertXmlToArray($this->getXmlData()->list_of_object->replace_list)['replace'];
        $unitInfo = UnitModel::where('code', $param['owner_code'])->first();
        $userInfo = UserModel::find($param['userId']);

        foreach ($arrVariableReplace as $key => $variableReplace) {
            $data[$variableReplace['template_variable']] = '';
            //kiểm tra xem có trường data của table không
            if (array_key_exists('data_of_table', $variableReplace) && $variableReplace['data_of_table'] == 'true') {
                unset($data[$variableReplace['template_variable']]);
                array_push($arrVariableReplaceTable,  $variableReplace);
                continue;
            }
            // trường hợp data là một trường trong record
            if (array_key_exists($variableReplace['field_name'], $dataSql)) {
                $phpfunction = $variableReplace['phpfunction'] ?? null;
                $data[$variableReplace['template_variable']] = empty($phpfunction) ?
                    $dataSql[$variableReplace['field_name']] :
                    FunctionHelper::$phpfunction($dataSql[$variableReplace['field_name']]);
                continue;
            }

            switch ($variableReplace['field_name']) {
                case 'GET_DATE': // lay ngay/thang/nam hien tai
                    $data[$variableReplace['template_variable']] = 'Ngày ' . date('d') . ' tháng ' . date('m') . ' năm ' . date('Y'); //dung ga
                    break;
                case 'STAFF_NAME': //lay ten cua tai khoan dang nhap in
                    $data[$variableReplace['template_variable']] = $userInfo->name;
                    break;
                case 'STAFF_UNIT_NAME':  //lay ten co quan cua tai khoan in
                    $data[$variableReplace['template_variable']] = $unitInfo->name;
                    break;
                case 'OWNER_NAME': //lay co quan in phieu them UY BAN NHAN DAN (neu khong phai so nganh)
                    if ($unitInfo->type_group == 'SO_NGANH') {
                        $name = Str::upper($unitInfo->name);
                    } else {
                        $name = Str::upper('UBND ' . $unitInfo->name);
                        $conf = config('moduleInitConfig.district');
                        foreach ($conf as $c) {
                            if ($c['code_branch'] == $unitInfo->code) {
                                $unitParent = UnitModel::where('code', $c['code'])->first();
                                $name = Str::upper($unitParent->name);
                                if ($unitParent->type_group != 'SO_NGANH') $name  = 'UBND ' . $name;
                            }
                        }
                    }
                    $data[$variableReplace['template_variable']] = $name;
                    break;
                case 'HANDLE_DEPARTMENT': //lay co quan in phieu
                    $department = UnitModel::where('id', $param['unitId'])->first();
                    $data[$variableReplace['template_variable']] = $department->name;
                    break;
                case 'OR_PHONE_CONTACT':
                    $str = '';
                    $conf = config('moduleInitConfig.district');
                    foreach ($conf as $c) {
                        if ($c['code_branch'] == $unitInfo->code && trim((string)$unitInfo->mobile) != '') {
                            $str = 'hoặc qua số điện thoại: ' . $unitInfo->mobile;
                            break;
                        }
                    }
                    $data[$variableReplace['template_variable']] = $str;
                default:
                    break;
            }
        }
        // set data them vao bang RECORD
        $configTableRecord = $arrVariableReplaceTable[0]['variable_column']['replace'];
        foreach ($dataSql  as $record) {
            $stt = 0;
            foreach ($configTableRecord  as $variableReplaceTable) {
                $record = (array)$record;
                if (array_key_exists($variableReplaceTable['field_name'], $record))
                    $temp[$variableReplaceTable['template_variable']] = $record[$variableReplaceTable['field_name']] ?? '';
                if ($variableReplaceTable['field_name'] == 'HANDLE_DEPARTMENT') {
                    $department = UnitModel::where('id', $param['unitId'])->first();
                    $temp[$variableReplaceTable['template_variable']] = $department->name;
                }
                if ($variableReplaceTable['field_name'] == 'RETURN_RESULT_ADDRESS') {
                    $temp[$variableReplaceTable['template_variable']] = FunctionHelper::_xmlGetXmlTagValue($record['xml_data'], 'data_list', 'return_result_address');
                }
            }
            if ($record['record_id'] == $param['idRecord'][0])
                $data['RECORD_NAME'] = $record['recordtype_name'];
            $dataTable['BAN_GHI'][] = $temp;
        }
        $this->dataReplace['data'] = $data;
        $this->dataReplace['table']['data'] = $dataTable;

        return $this;
    }

    public function getBarcode($barcode)
    {
        Storage::disk('public')->put($barcode . '.png', base64_decode(DNS1D::getBarcodePNG($barcode, 'C93', 1, 55)));
        return $barcode . '.png';
    }
}
