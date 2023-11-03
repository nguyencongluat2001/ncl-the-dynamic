<?php

namespace Modules\System\Listtype\Services;

use Illuminate\Support\Facades\DB;
use Modules\Core\Ncl\Library;
use Modules\Core\Ncl\LoggerHelpers;
use Modules\Core\Ncl\Xml;
use Modules\System\Listtype\Models\ListModel;
use Modules\System\Listtype\Models\ListtypeModel;
use Modules\System\Listtype\Repositories\ListRepository;
use Modules\System\Users\Models\UnitModel;

class ListService
{
    private $listRepository;
    private $logger;

    public function __construct(
        ListRepository $l,
        LoggerHelpers $lg
    ) {
        $this->listRepository = $l;
        $this->logger = $lg;
        $this->logger->setFileName('System_ListService');
    }

    /**
     * Các dữ liệu khởi tạo màn index
     * 
     * @return array
     */
    public function index(): array
    {
        $objLibrary = new Library();
        $arrResult = array();
        $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('js', 'system/listtype/Js_List.js', ',', $arrResult);
        $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('js', 'assets/chosen/chosen.jquery.min.js,assets/jquery.validate.js', ',', $arrResult);
        $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('css', 'assets/chosen/bootstrap-chosen.css', ',', $arrResult);
        $data['stringJsCss'] = json_encode($arrResult);
        // lay loai danh muc
        $data['arrListTypes'] = (new ListtypeModel())->_getAllbyStatus();
        return $data;
    }

    /**
     * Lấy dữ liệu
     * 
     * @param array $input
     * @return array
     */
    public function loadList(array $input): array
    {
        $currentPage = $input['currentPage'];
        $perPage = $input['perPage'];
        $search = $input['search'];
        $listtype = $input['listtype'] ?? '';
        $xmlFilename = 'quan_tri_doi_tuong_danh_muc.xml';
        $ListtypeModel = ListtypeModel::find($listtype);
        if (isset($ListtypeModel->xml_file_name) && $ListtypeModel->xml_file_name !== '') {
            $checkpathfile = base_path("xml\System\list") . '\\' . $ListtypeModel->xml_file_name;
            if (file_exists($checkpathfile)) {
                $xmlFilename = $ListtypeModel->xml_file_name;
            }
        }
        $xmlFilePath = url('/xml/System/list/' . $xmlFilename);
        // lay danh sach danh muc
        $objResult = $this->listRepository->getAll($listtype, $currentPage, $perPage, $search);

        return array(
            'Dataloadlist' => $objResult,
            'pagination'   => (string) $objResult->links('pagination.default'),
            'perPage'      => $perPage,
            'xmlFilePath'  => $xmlFilePath
        );
    }

    /**
     * Dữ liệu form thêm mới
     * 
     * @param array $input
     * @return array
     */
    public function create(array $input): array
    {
        $ListtypeModel = new ListtypeModel;
        $objxml = new Xml();
        $arrListtype = $ListtypeModel->_getSingle($input['listtype']);
        $xml_file_name = 'quan_tri_doi_tuong_danh_muc.xml';
        if (isset($arrListtype['xml_file_name']) && $arrListtype['xml_file_name'] !== '') {
            $xml_file_name = $arrListtype['xml_file_name'];
        }
        $sxmlFileName = base_path('xml\System\list\\' . $xml_file_name);
        $pathXmlTagStruct = 'update_object/table_struct_of_update_form/update_row_list';
        $pathXmlTag = 'update_object/update_formfield_list';
        $p_xml_string_in_db = '<?xml version="1.0" encoding="UTF-8"?><root><data_list></data_list></root>';
        $p_arr_item_value = array();
        $p_arr_item_value['order'] = ListModel::where('system_listtype_id', $input['listtype'])->count() + 1;
        $strrHtml = $objxml->xmlGenerateFormfield($sxmlFileName, $pathXmlTagStruct, $pathXmlTag, $p_xml_string_in_db, $p_arr_item_value);
        $listtype_name = ListtypeModel::find($input['listtype'])->name;
        $data = array(
            'strHTML'      => $strrHtml,
            'oldorder'      => $p_arr_item_value['order'],
            'listtype_id'   => $input['listtype'],
            'idlist'        => '',
            'listtype_name' => $listtype_name
        );
        $data['data']['id'] = '';
        $data['Units'] = [];
        $data['data']['ownercode'] = $data['Units'];
        $data['listtype'] = '';

        return $data;
    }

    /**
     * Dữ liệu form sửa
     * 
     * @param array $input
     * @return array
     */
    public function edit(array $input): array
    {
        $objXml = new Xml();
        $ListModel = new ListModel;
        $ListtypeModel = new ListtypeModel;
        $itemid = $input['itemId'];
        $arrListtype = $ListtypeModel->_getSingle($input['listtype']);
        $xml_file_name = 'quan_tri_doi_tuong_danh_muc.xml';
        if (isset($arrListtype['xml_file_name']) && $arrListtype['xml_file_name'] !== '') {
            $checkpathfile = base_path("xml\System\list") . '\\' . $arrListtype['xml_file_name'];
            if (file_exists($checkpathfile)) {
                $xml_file_name = $arrListtype['xml_file_name'];
            }
        }
        $sxmlFileName = base_path('xml\System\list\\' . $xml_file_name);
        $pathXmlTagStruct = 'update_object/table_struct_of_update_form/update_row_list';
        $pathXmlTag = 'update_object/update_formfield_list';
        $p_arr_item_value = $ListModel->_getSingle($itemid);
        $strHtml = $objXml->xmlGenerateFormfield($sxmlFileName, $pathXmlTagStruct, $pathXmlTag, 'xml_data', $p_arr_item_value);
        // $UnitRoot = UnitModel::where("units_id", '=', NULL)->get()->toArray();
        // $Units = UnitModel::where("units_id", '=', $UnitRoot[0]['units_id'])->orderby('type_group')->get();
        $listtype_name = ListtypeModel::find($input['listtype'])->name;
        $data = array(
            'strHTML' => $strHtml,
            'oldorder' => $p_arr_item_value['order'],
            'listtype_id' => $input['listtype'],
            'idlist' => $itemid,
            'listtype_name' => $listtype_name
        );
        // $data['Units'] = $Units;
        $data['data']['ownercode'] = $p_arr_item_value['owner_code_list'];
        $data['listtype'] = $input['listtype'];

        return $data;
    }

    /**
     * Update hoặc thêm mới
     * 
     * @param array $input
     * @return array
     */
    public function update(array $input): array
    {
        if ($input['code'] == '') return array('success' => false, 'message' => 'Mã không được để trống');
        if ($input['name'] == '') return array('success' => false, 'message' => 'Tên không được để trống');
        $idlist = $input['idlist'];
        if (!$idlist) {
            $checkDuplicate = ListModel::where(['system_listtype_id' => $input['listtype'], 'code' => $input['code']])->exists();
            if ($checkDuplicate) return array('success' => false, 'message' => 'Đối tượng đã tồn tại');
        }
        $params = array(
            'code'               => $input['code'],
            'system_listtype_id' => $input['listtype'],
            'name'               => $input['name'],
            'order'              => $input['order'],
            'xml_data'           => $input['stringxml'],
            'status'             => ($input['status'] === 'on') ? 1 : 0,
            'owner_code_list'    => "All"
        );
        DB::beginTransaction();
        try {
            $this->listRepository->update($idlist, $params);
            DB::commit();
            return array('success' => true, 'message' => 'Cập nhật thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logger->setChannel('UPDATE_LIST')
                ->log('Exception: ' . $e->getMessage() . ' on file ' . $e->getFile() . ' in line ' . $e->getLine());

            return array('success' => false, 'message' => 'Cập nhật thất bại');
        }
    }

    /**
     * Xóa
     * 
     * @param string $listitem Danh sách các đối tượng cần xóa
     * @return array
     */
    public function delete(string $listitem): array
    {
        DB::beginTransaction();
        try {
            $this->listRepository->delete($listitem);
            DB::commit();
            return array('success' => true, 'message' => 'Xóa thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logger->setChannel('DELETE_LIST')
                ->log('Exception: ' . $e->getMessage() . ' on file ' . $e->getFile() . ' in line ' . $e->getLine());

            return array('success' => false, 'message' => 'Xóa thất bại');
        }
    }
}
