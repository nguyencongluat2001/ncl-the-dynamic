<?php

namespace Modules\Core\Ncl\FormDynamic;

use Modules\Core\Ncl\FormDynamic\FormDynamic;
use Modules\Core\Ncl\FormDynamic\listForm\QueryBuilder;
use Modules\Core\Ncl\Exceptions\ResponseExeption;

class ListFormDynamic extends FormDynamic
{

    private $columnMerge = ['tentochuccanhan', 'thongtinhoso', 'diachi'];

    public function init($module, $action)
    {
        $codeModule = trim("module_" . $module);
        $codeAction = trim("action_" . $action);
        $xmlPath    = "xml/Record/" . $codeModule . "/" . $codeAction . "/" . $codeAction . "_index.xml";

        if (!file_exists($xmlPath)) {
            throw new ResponseExeption("xml: " . $xmlPath . " Chưa định nghĩa");
        }
        $this->setXmlData($xmlPath);
    }

    public function getRecord($filters)
    {
        $QueryBuilder = new QueryBuilder();
        $xmlSqls = (array)$this->getXmlData()->list_of_object->list_sql;
        if (!isset($xmlSqls['type'])) {
            return false;
        }
        $type = $xmlSqls['type'];
        $QueryBuilder->init($xmlSqls, $filters);
        return $QueryBuilder->getData();
    }

    public function getLabel()
    {
        return (string)$this->getXmlData()->common_para_list->common_para->list_form_title;
    }

    public function getModule()
    {
        return (string)$this->getXmlData()->common_para_list->common_para->module;
    }

    public function getAction()
    {
        return (string)$this->getXmlData()->common_para_list->common_para->action;
    }

    public function getEventtable()
    {
        $arrBody = $this->getXmlData()->list_of_object->list_body->col;
        $data = array();
        $i = 0;
        foreach ($arrBody as $key => $arrValue) {
            $label    = (string)$arrValue->label;
            $label    = htmlspecialchars($label);
            $width    = (string)$arrValue->width;
            $code     = (string)$arrValue->code;
            $align    = (string)$arrValue->align;
            $data[$i] = compact('label', 'width', 'code', 'align');
            $i++;
        }
        // các column gộp chung
        foreach ($data as $key => $value) {
            if (!in_array($value['code'], $this->columnMerge)) continue;
            if (array_search('thongtin', array_column($data, 'code')) !== false) {
                $k = array_search('thongtin', array_column($data, 'code'));
                $data[$k]['width'] = ((int)$data[$k]['width'] + (int)$value['width']) . '%';
                unset($data[$key]);
            } else {
                $data[$key]['code']  = 'thongtin';
                $data[$key]['label'] = 'Thông tin hồ sơ';
            }
        }

        return array_values($data);
    }
}
