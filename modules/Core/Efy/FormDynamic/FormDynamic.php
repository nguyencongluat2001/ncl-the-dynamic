<?php

namespace Modules\Core\Efy\FormDynamic;

use Modules\Core\Efy\FormDynamic\QueryBuilder;

use Modules\Core\Efy\FormDynamic\eForm\eFormConvert;
use Modules\Core\Efy\FormDynamic\eForm\ValidateData;

class FormDynamic {

    protected $xmlData;
    protected $xmlforms;

    public function setXmlData($xmlPath){
        $this->xmlData = $this->getContentXml($xmlPath);
    }

    public function getXmlData(){
        return $this->xmlData;
    }

    public function getContentXml($xmlPath){
        if (!file_exists($xmlPath)) {
            $xmlPath = base_path($xmlPath);
        }
        $stringxmlcontent = file_get_contents($xmlPath);
        return simplexml_load_string($stringxmlcontent);
    }

    public function convertXmlToArray($xmlstring){
		if($xmlstring){
			$json = json_encode($xmlstring);
 			$returnArray = json_decode($json, true);
		}else{
			$returnArray = false;
		}
		return $returnArray;
	}

}