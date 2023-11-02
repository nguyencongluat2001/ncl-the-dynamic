<?php

namespace Modules\Core\Efy\PrintDynamic;

use Illuminate\Support\Facades\Http;
use Modules\Core\Efy\Exceptions\ResponseExeption;
use Modules\Core\Efy\FormDynamic\eForm\eFormConvert;
use Modules\Core\Efy\FormDynamic\eForm\ValidateData;
use Modules\Core\Efy\FormDynamic\FormDynamic;

class PdfPrintDynamic extends PrintDynamic {
    use PrintDynamicTrait;

    public function export($param)
    {
        $url_export = config('app.url_export');

        $record = $this->getRecord($param)['data'];

        if(empty($record)){
            throw new ResponseExeption("Không tồn tại đối tượng!");
        }
        if ($param['code'] == 'PHIEU_BAN_GIAO' || $param['code'] == 'PHIEU_BAN_GIAO_BUU_DIEN') {
            $dataSql = (array)$record;
            $this->setDataReplaceBanGiao($param, $dataSql);
        }
        if(sizeof($record) == 1 && $param['code'] != 'PHIEU_BAN_GIAO' && $param['code'] != 'PHIEU_BAN_GIAO_BUU_DIEN'){
            $dataSql = (array)$record[0];
            $this->setDataReplaceNew($param, $dataSql);
        }


        $this->replaceData();

        $exportPath = storage_path('export') . '/' . $this->nameExport . ".docx";
        $this->templateProcessor->saveAs($exportPath);
        $data['data']['is_return_file_pdf'] = true;
        $data['data']['file_name'] = $this->nameExport . ".docx";
        $data['data']['app_code'] = "efy";
        $data['data']['file_content'] = base64_encode(file_get_contents($exportPath));
        $response = Http::withHeaders([
            'Authorization' => "Basic " . base64_encode($this->username . ':' . $this->password)
        ])->post($url_export, $data);
        unlink($exportPath);
        $response = json_decode($response, true);
        if (isset($response['file_content'])) {

            $templateContent = base64_decode($response['file_content']);
            $content_type = "application/pdf";
            header("Content-type:$content_type");
            header("Content-Disposition: ;filename=" . $this->nameExport . ".pdf");
            print($templateContent);
        }
        return [];
    }

}
