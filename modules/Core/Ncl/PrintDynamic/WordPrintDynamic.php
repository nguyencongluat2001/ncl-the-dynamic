<?php

namespace Modules\Core\Ncl\PrintDynamic;

use Modules\Core\Ncl\Exceptions\ResponseExeption;
use Modules\Core\Ncl\FormDynamic\eForm\eFormConvert;
use Modules\Core\Ncl\FormDynamic\eForm\ValidateData;
use Modules\Core\Ncl\FormDynamic\FormDynamic;

class WordPrintDynamic extends PrintDynamic {
    use PrintDynamicTrait;

    public function export($param)
    {
        $record = $this->getRecord($param)['data'];

        if(empty($record)){
            throw new ResponseExeption("Không tồn tại đối tượng!");
        }
        if ($param['code'] == 'PHIEU_BAN_GIAO' || $param['code'] == 'PHIEU_BAN_GIAO_BUU_DIEN') {
            $dataSql = (array)$record;
            $this->setDataReplaceBanGiao($param, $dataSql);
        }
        if(sizeof($record) == 1 && $param['code'] != 'PHIEU_BAN_GIAO') {
            $dataSql = (array)$record[0];
            $this->setDataReplaceNew($param, $dataSql);
        }

        $this->replaceData();

        $exportPath = storage_path('export') . '/' . $this->nameExport . ".docx";
        $this->templateProcessor->saveAs($exportPath);
        $return['url'] =  url('storage/export/' . $this->nameExport . ".docx");
        return $return;
    }
}
