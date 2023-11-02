<?php

namespace Modules\System\Exams\Helpers;

use Modules\System\Listtype\Models\ListModel;
use Modules\System\Listtype\Models\ListtypeModel;

/**
 * Helper xử lý liên quan đến danh mục
 *
 * @author Toanph <skype: toanph1505>
 */
class ListtypeHelper
{

    /**
     * Lấy danh mục từ một danh mục đối tượng
     *
     * @return array
     */
    public function _GetSingleListObjectByListCode($sCode, $value, $typeJson = false)
    {
        $arrObject = ListtypeModel::_getSinglebyCode($sCode, $value, $typeJson);
        return $arrObject;
    }

    /**
     * Lấy tất cả danh mục từ một danh mục đối tượng
     *
     * @return array
     */
    public function _GetAllListObjectByListCode($sCode, $arrcolumn = array('*'), $typeJson = false, $isDB = false)
    {
        return (new ListModel())->_getAllbyCodeAStatus($sCode, $typeJson, $arrcolumn, $isDB);
    }

    /**
     * Lấy tất cả danh mục từ một danh mục đối tượng và sắp xếp theo name
     *
     * @return array
     */
    public function _GetAllListObjectByListCodeOrderByName($sCode, $arrcolumn = array('*'), $typeJson = false, $isDB = false)
    {
        $ListModel = new ListModel();
        $arrObject = $ListModel->_getAllbyCodeAStatusOrderByName($sCode, $typeJson, $arrcolumn, $isDB);
        return $arrObject;
    }

    public function getValueByKeyListType($key, $datas)
    {
        foreach ($datas as $data) {
            if ($data['code'] == $key) {
                return $data['name'];
            }
        }
        return "";
    }
}
