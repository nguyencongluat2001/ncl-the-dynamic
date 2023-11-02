<?php

namespace Modules\Core\Efy\FormDynamic\eForm;

use Modules\Core\Efy\FormDynamic\eForm\EformListtypeHelper;
use Modules\Core\Efy\Exceptions\ResponseExeption;

class ValidateData
{

    public $checkSuccess = true;
    public $columnUpdate;

    public function run($data, $xmlforms)
    {
        $arrError = array();
        $arrUpdateColumn = array();
        $arrUpdateJson = array();
        if ($xmlforms) {
            foreach ($xmlforms as $key => $xmlform) {
                if ($xmlform) {
                    // kiem tra required
                    if (isset($xmlform->required) && $xmlform->required == "true") {
                        if (!isset($data[$key]) || $data[$key] == '') {
                            throw new ResponseExeption($xmlform->label . " is not required");
                            $this->checkSuccess = false;
                        }
                    }
                    // Kiem tra du lieu update vao column hay database
                    if (isset($xmlform->xml_tag_in_db) && $xmlform->xml_tag_in_db <> '') {
                        $arrUpdateJson[$key] = $xmlform->xml_tag_in_db;
                    } 
                    if (isset($xmlform->column_name) && $xmlform->column_name <> '') {
                        $arrUpdateColumn[$key] = $xmlform->column_name;
                    }
                }
            }
        }
        $this->zendDbColumn($arrUpdateColumn,$data);
    }

    private function zendDbColumn($datas, $values)
    {
        $return = array();
        $strColumn = $strValue = '';
        if (sizeof($datas) > 0) {
            foreach ($datas as $key => $data) {
                if ($data) {
                    if (isset($values[$key]) && !empty($values[$key]) && !is_array($values[$key])) {
                        $strValue .= $values[$key] . '!##!';
                        $strColumn .= $data . '!##!';
                    }
                }
            }
        }
      
        $column = explode("!##!", trim($strColumn, "!##!"));
        $value =  explode("!##!", trim($strValue, "!##!"));
        $this->columnUpdate = compact("column","value");
    }
}
