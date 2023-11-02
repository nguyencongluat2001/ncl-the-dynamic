<?php

namespace Modules\Core\Efy\FormDynamic;

use Modules\Core\Efy\FormDynamic\FormDynamic;
use Modules\Core\Efy\FormDynamic\eForm\eFormConvert;
use Modules\Core\Efy\FormDynamic\eForm\ValidateData;

class EFormDynamic extends FormDynamic
{

    private $pathXml = 'xml\Recordtype\other\ho_so_da_tiep_nhan.xml';
    private $columnUpdate;

    public function init($code)
    {
        $sxmlFileName = 'ho_so_da_tiep_nhan.xml';
        $basepath = base_path() . "/xml/Recordtype/";
        $filexml = $basepath . $code . '/' . $sxmlFileName;
        if (file_exists($filexml)) {
            $this->pathXml = "/xml/Recordtype/" . $code . '/' . $sxmlFileName;
        }
    }

    public function getForm()
    {
        $eFormConvert = new eFormConvert();
        $xmlString = $this->getContentXml($this->pathXml);
        return $eFormConvert->GenerateForm($this->convertXmlToArray($xmlString));
    }

    public function validate($data)
    {
        $ValidateData = new ValidateData();
        $objConfigXml = $this->getContentXml($this->pathXml);
        $this->xmlforms = (array)$objConfigXml->update_object->update_formfield_list;
        $ValidateData->run($data, $this->xmlforms);
        $this->columnUpdate = $ValidateData->columnUpdate;
        return $ValidateData->checkSuccess;
    }

    public function getColumnUpdate()
    {
        return $this->columnUpdate;
    }
}
