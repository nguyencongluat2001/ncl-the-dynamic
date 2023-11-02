<?php

namespace Modules\Core\Efy\PrintDynamic;

use DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Api\Models\Admin\RecordtypeModel;
use Modules\Api\Models\Admin\UnitModel;
use Modules\Core\Efy\Exceptions\ResponseExeption;
use Modules\Core\Efy\FormDynamic\eForm\eFormConvert;
use Modules\Core\Efy\FormDynamic\eForm\ValidateData;
use Modules\Core\Efy\FormDynamic\FormDynamic;
use Modules\Core\Efy\PrintDynamic\Excel\ExcelObject;
use Modules\Core\Efy\PrintDynamic\Excel\ExcelObjectNew;

class ExcelPrintDynamic extends PrintDynamic
{
    use PrintDynamicTrait;

    private $nameStoredProcedure;
    private $arrParamStoredProcedure;
    private $stringSP;
    private $dataStoredProcedure;
    private $dataFooter;

    public function export($data)
    {
        $this->setDataReplaceExcel($data);
        $url = url(ltrim(public_path('reports'), base_path()));
        Excel::store(new ExcelObjectNew($data['sql'], $this->dataReplace, $this->dataFooter, $this->fileTemplate), $this->nameExport . '.xlsx', 'real_public');
        return response()->json(['url' => $url . '/' . $this->nameExport . '.xlsx']);
    }

    public function setDataReplaceExcel($data)
    {
        $input = $data['input'];
        // $dataSql = $data['sql'];
        $unitInfo = UnitModel::where('code', $input['ownerCode'])->first();
        $xml = (array)$this->getXmlData()->list_of_object->replace_list;
        $arrConfigTable = $this->convertXmlToArray($this->getXmlData()->list_of_object->replace_list->table);
        $arrConfigFooter = $this->convertXmlToArray($this->getXmlData()->list_of_object->replace_list->tfooter);

        foreach ($xml['replace'] as $replace) {
            $name_string = (string)$replace->name_string;
            $cell = (string)$replace->cell;
            switch ($name_string) {
                case 'OWNERNAME':
                    $configCells[$cell] = trim($unitInfo->name);
                    break;
                case 'GETSTATUS':
                    $configCells[$cell] = trim($unitInfo->name) . ', ngày ' . date('d') . ' tháng ' . date('m') . ' năm ' . date('Y');
                    break;
                case 'FROMDATE_TODATE':
                    $configCells[$cell] = 'Từ ngày: ' . date("d/m/Y", strtotime($input['fromDate'])) .
                        ' đến ngày ' . date("d/m/Y", strtotime($input['toDate']));
                    break;
                case 'RECORDTYPES': // Lấy ra loại hồ sơ
                    if (isset($input['procedure']) && !empty($input['procedure'])) {
                        $recordTypes = RecordtypeModel::whereIn('code', $input['procedure'])->pluck('name')->toArray();
                    }
                    $stringRecordType = isset($recordTypes) ? implode(',', $recordTypes) : '';
                    $configCells[$cell] = 'Loại hồ sơ: ' . $stringRecordType;
                    break;
                default:
                    break;
            }
        }

        $this->dataFooter['configTableCells'] = !empty($arrConfigFooter) ? $arrConfigFooter['column'] : '';
        $this->dataReplace['iteration'] = $arrConfigTable['iteration'];
        $this->dataReplace['configCells'] = $configCells;
        $this->dataReplace['configTableCells'] = $arrConfigTable['column'];
        $this->dataReplace['configTableMergeRow'] = $arrConfigTable['merge_row'] ?? null;

        return $this;
    }

    public function setParamStoredProcedure($data)
    {
        $xmlSP = (array)$this->getXmlData()->list_of_object->stored_procedure;
        $stringSP = 'exec ' . 'test_BC_01';
        $stringParam = '';
        foreach ($xmlSP['param'] as $param) {
            $index = (string)$param->index;
            $name = (string)$param->name;
            $this->arrParamStoredProcedure[$index] = $name;
        }
        foreach ($this->arrParamStoredProcedure as $paramStoredProcedure) {
            if (array_key_exists($paramStoredProcedure, $data)) {
                $stringParam = $stringParam . '"' . $data[$paramStoredProcedure] . '",';
            }
        }
        $stringParam = rtrim($stringParam, ",");

        if ($stringParam != '') {
            $stringSP = $stringSP . ' ' . $stringParam;
        }
        $this->stringSP  = $stringSP;
        return $this;
    }

    public function getDataByStoredProcedure()
    {
        $data = DB::connection('sqlsrvEcs')->select($this->stringSP);
        $this->dataStoredProcedure = $data;
        return $this;
    }
}
