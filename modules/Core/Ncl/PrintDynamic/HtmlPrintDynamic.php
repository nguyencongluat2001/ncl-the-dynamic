<?php

namespace Modules\Core\Ncl\PrintDynamic;

use Modules\Core\Ncl\Exceptions\ResponseExeption;

class HtmlPrintDynamic extends PrintDynamic
{
    use PrintDynamicTrait;

    public function export($param)
    {
        $record = $this->getRecord($param)['data'];

        if (empty($record)) {
            throw new ResponseExeption("Không tồn tại đối tượng!");
        }

        if (isset($record[0]->xml_data)) {
            $xmlData = simplexml_load_string($record[0]->xml_data);
            $xmlData = json_decode(json_encode($xmlData), true);
            if (isset($xmlData['data_list']['registor_note']) && !empty($xmlData['data_list']['registor_note'])) {
                $record[0]->recordtype_name = $xmlData['data_list']['registor_note'];
            }
        }

        if ($param['code'] == 'PHIEU_BAN_GIAO' || $param['code'] == 'PHIEU_BAN_GIAO_BUU_DIEN') {
            $dataSql = (array)$record;
            $this->setDataReplaceBanGiao($param, $dataSql);
        }
        if (sizeof($record) == 1 && $param['code'] != 'PHIEU_BAN_GIAO' && $param['code'] != 'PHIEU_BAN_GIAO_BUU_DIEN') {
            $dataSql = (array)$record[0];
            $this->setDataReplaceNew($param, $dataSql);
        }

        $return = $this->PrintToHTML($param['code']);

        return $return;
    }

    public function PrintToHTML($typeForm)
    {
        //Doc noi dung file templace
        $sContentFile = file_get_contents($this->fileTemplate);
        // Thay data
        if (isset($this->dataReplace['data']) && sizeof($this->dataReplace['data']) > 0) {
            foreach ($this->dataReplace['data'] as $key => $value) {
                $sFindSstring = '#' . $key . '#';
                $sContentFile = str_replace($sFindSstring, !empty($value) ? $value : null, $sContentFile);
            }
        }

        // Thay ảnh
        if (isset($this->dataReplace['image']) && sizeof($this->dataReplace['image']) > 0) {
            foreach ($this->dataReplace['image'] as $key => $value) {
                $path = url('storage/app/public/' . $value);
                $sFindSstring = '#' . $key . '#';
                $sContentFile = str_replace($sFindSstring, !empty($path) ? $path : null, $sContentFile);
            }
        }

        // Thay data trong bảng
        if (isset($this->dataReplace['table']) && !empty($this->dataReplace['table']['data'])) {
            $dataTable = count($this->dataReplace['table']['data']) >= 2
                ? current($this->dataReplace['table']['data'])
                : current($this->dataReplace['table']['data']);
            $sValue = '';
            $stt = 1;
            foreach ($dataTable as $key => $valueTables) {
                switch ($typeForm) {
                    case 'PHIEU_BIEN_NHAN':
                        $sValue = $sValue . '<tr><td style="text-align:center;padding:6px;font-size:10.0pt
                        line-height:125%; border:1px solid black">' . $valueTables['STT'] . '</td>
                        <td style="padding:6px;font-size:10.0pt
                        line-height:125%; border:1px solid black">' . $valueTables['TEN_HO_SO'] . '</td>
                        <td style="padding:6px;font-size:10.0pt
                        line-height:125%; border:1px solid black;text-align:center">' . $valueTables['SL'] . '</td></tr>';
                        break;
                    case 'PHIEU_BAN_GIAO':
                        $sValue = $sValue . '<tr><td style="text-align:center;padding:6px;font-size:10.0pt
                        line-height:125%; border:1px solid black">' . $stt++ . '</td>
                        <td style="padding:6px;font-size:10.0pt
                        line-height:125%; border:1px solid black">' . $valueTables['RECORD_CODE'] . '</td>
                        <td style="padding:6px;font-size:10.0pt
                        line-height:125%; border:1px solid black">' . $valueTables['REGISTOR_NAME'] . '</td>
                        <td style="padding:6px;font-size:10.0pt
                        line-height:125%; border:1px solid black">' . $valueTables['REGISTOR_ADDRESS'] . '</td>
                        <td style="padding:6px;font-size:10.0pt
                        line-height:125%; border:1px solid black">' . $valueTables['HANDLE_DEPARTMENT'] . '</td>
                        <td style="padding:6px;font-size:10.0pt
                        line-height:125%; border:1px solid black">' . $valueTables['RECEIVED_DATE'] . '</td>
                        <td style="padding:6px;font-size:10.0pt
                        line-height:125%; border:1px solid black;text-align:center">' . $valueTables['APPOINTED_DATE'] . '</td></tr>';
                        break;
                    case 'PHIEU_BAN_GIAO_BUU_DIEN':
                        $sValue = $sValue . '<tr><td style="text-align:center;padding:6px;font-size:10.0pt
                        line-height:125%; border:1px solid black">' . $stt++ . '</td>
                        <td style="padding:6px;font-size:10.0pt
                        line-height:125%; border:1px solid black">' . $valueTables['RECORD_CODE'] . '</td>
                        <td style="padding:6px;font-size:10.0pt
                        line-height:125%; border:1px solid black">' . $valueTables['REGISTOR_NAME'] . '</td>
                        <td style="padding:6px;font-size:10.0pt
                        line-height:125%; border:1px solid black">' . $valueTables['RETURN_RESULT_ADDRESS'] . '</td>
                        <td style="padding:6px;font-size:10.0pt
                        line-height:125%; border:1px solid black">' . '' . '</td>
                        <td style="padding:6px;font-size:10.0pt
                        line-height:125%; border:1px solid black">' . $valueTables['RECEIVED_DATE'] . '</td>
                        <td style="padding:6px;font-size:10.0pt
                        line-height:125%; border:1px solid black">' . $valueTables['APPOINTED_DATE'] . '</td></tr>';
                        break;
                    default:
                        break;
                }
            }
            switch ($typeForm) {
                case 'PHIEU_BIEN_NHAN':
                    //#INCLUDE_DOCUMENT# là chuỗi được định nghĩa trong template
                    $sContentFile = str_replace("#INCLUDE_DOCUMENT#", $sValue, $sContentFile);
                    break;
                case 'PHIEU_BAN_GIAO':
                    //#INCLUDE_DOCUMENT# là chuỗi được định nghĩa trong template
                    $sContentFile = str_replace("#TABLE_INFO_RECORD#", $sValue, $sContentFile);
                    break;
                case 'PHIEU_BAN_GIAO_BUU_DIEN':
                    $sContentFile = str_replace("#TABLE_INFO_RECORD#", $sValue, $sContentFile);
                    break;
                default:
                    break;
            }
        }

        // Thay data trong list
        if (isset($this->dataReplace['list']) && !empty($this->dataReplace['list']['data'])) {
            $dataList = count($this->dataReplace['list']['data']) >= 2
                ? current($this->dataReplace['list']['data'])
                : current($this->dataReplace['list']['data']);
            $sValue = '';
            $stt = 1;
            foreach ($dataList as $key => $valueList) {
                switch ($typeForm) {
                    case 'PHIEU_BIEN_NHAN':
                    case 'PHIEU_BIEN_NHAN_DN':
                        $sValue .= '<li>' . $valueList['TEN_HO_SO'] . '</li>';
                        break;
                    case 'PHIEU_BAN_GIAO':
                        $sValue = $sValue . '<tr><td style="text-align:center;padding:6px;font-size:10.0pt
                        line-height:125%; border:1px solid black">' . $stt++ . '</td>
                        <td style="padding:6px;font-size:10.0pt
                        line-height:125%; border:1px solid black">' . $valueList['RECORD_CODE'] . '</td>
                        <td style="padding:6px;font-size:10.0pt
                        line-height:125%; border:1px solid black">' . $valueList['REGISTOR_NAME'] . '</td>
                        <td style="padding:6px;font-size:10.0pt
                        line-height:125%; border:1px solid black">' . $valueList['REGISTOR_ADDRESS'] . '</td>
                        <td style="padding:6px;font-size:10.0pt
                        line-height:125%; border:1px solid black">' . $valueList['HANDLE_DEPARTMENT'] . '</td>
                        <td style="padding:6px;font-size:10.0pt
                        line-height:125%; border:1px solid black">' . $valueList['RECEIVED_DATE'] . '</td>
                        <td style="padding:6px;font-size:10.0pt
                        line-height:125%; border:1px solid black;text-align:center">' . $valueList['APPOINTED_DATE'] . '</td></tr>';
                        break;
                    case 'PHIEU_BAN_GIAO_BUU_DIEN':
                        $sValue = $sValue . '<tr><td style="text-align:center;padding:6px;font-size:10.0pt
                        line-height:125%; border:1px solid black">' . $stt++ . '</td>
                        <td style="padding:6px;font-size:10.0pt
                        line-height:125%; border:1px solid black">' . $valueList['RECORD_CODE'] . '</td>
                        <td style="padding:6px;font-size:10.0pt
                        line-height:125%; border:1px solid black">' . $valueList['REGISTOR_NAME'] . '</td>
                        <td style="padding:6px;font-size:10.0pt
                        line-height:125%; border:1px solid black">' . $valueList['RETURN_RESULT_ADDRESS'] . '</td>
                        <td style="padding:6px;font-size:10.0pt
                        line-height:125%; border:1px solid black">' . '' . '</td>
                        <td style="padding:6px;font-size:10.0pt
                        line-height:125%; border:1px solid black">' . $valueList['RECEIVED_DATE'] . '</td>
                        <td style="padding:6px;font-size:10.0pt
                        line-height:125%; border:1px solid black">' . $valueList['APPOINTED_DATE'] . '</td></tr>';
                        break;
                    default:
                        break;
                }
            }
            switch ($typeForm) {
                case 'PHIEU_BIEN_NHAN':
                case 'PHIEU_BIEN_NHAN_DN':
                    //#INCLUDE_DOCUMENT_LIST# là chuỗi được định nghĩa trong template
                    $sContentFile = str_replace("#INCLUDE_DOCUMENT_LIST#", $sValue, $sContentFile);
                    break;
                case 'PHIEU_BAN_GIAO':
                    $sContentFile = str_replace("#TABLE_INFO_RECORD_LIST#", $sValue, $sContentFile);
                    break;
                case 'PHIEU_BAN_GIAO_BUU_DIEN':
                    $sContentFile = str_replace("#TABLE_INFO_RECORD_LIST#", $sValue, $sContentFile);
                    break;
                default:
                    break;
            }
        }

        return $sContentFile;
    }
}
