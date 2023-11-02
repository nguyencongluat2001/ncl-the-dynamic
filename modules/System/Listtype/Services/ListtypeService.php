<?php

namespace Modules\System\Listtype\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Core\Efy\Library;
use Modules\Core\Efy\LoggerHelpers;
use Modules\Core\Efy\Xml;
use Modules\System\Listtype\Models\ListtypeModel;
use Modules\System\Listtype\Repositories\ListtypeRepository;
use Modules\System\Users\Models\UnitModel;

class ListtypeService
{
    private $listtypeRepository;
    private $logger;

    public function __construct(
        ListtypeRepository $listtypeRepository,
        LoggerHelpers $logger
    ) {
        $this->listtypeRepository = $listtypeRepository;
        $this->logger = $logger;
        $this->logger->setFileName('System_ListtypeService');
    }

    /**
     * Dữ liệu khởi tạo màn index
     * 
     * @return array
     */
    public function index(): array
    {
        $objLibrary = new Library();
        $arrResult = array();
        $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('js', 'system/Listtype/Js_Listtype.js', ',', $arrResult);
        $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('js', 'assets/chosen/chosen.jquery.min.js,assets/jquery.validate.js', ',', $arrResult);
        $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('css', 'assets/chosen/bootstrap-chosen.css', ',', $arrResult);
        $data['stringJsCss'] = json_encode($arrResult);
        $data['Units'] = [];

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
        $objResult = $this->listtypeRepository->getAll($currentPage, $perPage, $search);

        return array(
            'Dataloadlist' => $objResult,
            'pagination'   => (string) $objResult->links('pagination.default'),
            'perPage'      => $perPage,
        );
    }

    /**
     * Dữ liệu view thêm mới
     * 
     * @param array $input
     * @return array
     */
    public function create(array $input): array
    {
        $xmlFilename = $input['_filexml'];
        $sxmlFileName = base_path('xml\System\listtype\\' . $xmlFilename);
        $pathXmlTagStruct = 'update_object/table_struct_of_update_form/update_row_list';
        $pathXmlTag = 'update_object/update_formfield_list';
        $xmlStr = '<?xml version="1.0" encoding="UTF-8"?><root><data_list></data_list></root>';
        // lay so thu tu lon nhat
        $arrItemValue = array();
        if (ListtypeModel::count() == 0) {
            $arrItemValue['order'] = 1;
        } else {
            $p_arr_item = ListtypeModel::orderBy('order', 'desc')->take(1)->first();
            $arrItemValue['order'] = (int)$p_arr_item->order + 1;
        }
        $arrItemValue['status'] = 1;
        $strHtml = (new Xml())->xmlGenerateFormfield($sxmlFileName, $pathXmlTagStruct, $pathXmlTag, $xmlStr, $arrItemValue);
        $data['strHTML'] = $strHtml;
        $data['data']['id'] = '';
        $data['Units'] = [];
        $data['data']['strHTML'] = $strHtml;
        $data['data']['id'] = '';
        $data['data']['listtype_xml'] = $xmlFilename;
        $data['data']['ownercode'] = '';

        return $data;
    }

    /**
     * Dữ liệu sửa listtype
     * 
     * @param array $input
     * @return array
     */
    public function edit(array $input): array
    {
        $ListtypeModel = new ListtypeModel;
        $xmlFilename = $input['_filexml'];
        $sxmlFileName = base_path('xml\System\listtype\\' . $xmlFilename);
        $pathXmlTagStruct = 'update_object/table_struct_of_update_form/update_row_list';
        $pathXmlTag = 'update_object/update_formfield_list';
        $xmlStr = '<?xml version="1.0" encoding="UTF-8"?><root><data_list></data_list></root>';
        $itemid = $input['itemId'];
        $arrItemValue = $ListtypeModel->_getSingle($itemid);
        $strrHtml = (new Xml())->xmlGenerateFormfield($sxmlFileName, $pathXmlTagStruct, $pathXmlTag, $xmlStr, $arrItemValue);
        // lay don vi trien khai
        $data['Units'] = [];
        $data['strHTML'] = $strrHtml;
        $data['data']['strHTML'] = $strrHtml;
        $data['data']['id'] = $itemid;
        $data['data']['listtype_xml'] = $xmlFilename;
        $data['data']['ownercode'] = $arrItemValue['owner_code_list'];

        return $data;
    }

    /**
     * Cập nhật hoặc thêm mới danh mục
     * 
     * @param array $input
     * @return array
     */
    public function update(array $input): array
    {
        $idListtype = $input['listtype_id'];
        $params = array(
            'code' => $input['code'],
            "name" => $input["name"],
            'order' => $input['order'],
            'xml_file_name' => $input['xml_file_name'],
            'status' => ($input['status'] === 'on') ? 1 : 0,
            'owner_code_list' => 'All',
        );
        if (ListtypeModel::where('code', $input['code'])->exists()) {
            $params['updated_at'] = Carbon::now();
        } else {
            $params['created_at'] = Carbon::now();
            $params['updated_at'] = Carbon::now();
        }
        DB::beginTransaction();
        try {
            $this->listtypeRepository->update($idListtype, $params);
            DB::commit();
            return array('success' => true, 'message' => 'Cập nhật thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logger->setChannel('UPDATE_LISTTYPE')
                ->log('Exception: ' . $e->getMessage() . ' on file ' . $e->getFile() . ' in line ' . $e->getLine());

            return array('success' => false, 'message' => 'Cập nhật thất bại');
        }
    }

    /**
     * Xóa danh mục
     * 
     * @param string $listitem
     * @return array
     */
    public function delete(string $listitem): array
    {
        DB::beginTransaction();
        try {
            $this->listtypeRepository->delete($listitem);
            DB::commit();
            return array('success' => true, 'message' => 'Xóa thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logger->setChannel('DELETE_LISTTYPE')
                ->log('Exception: ' . $e->getMessage() . ' on file ' . $e->getFile() . ' in line ' . $e->getLine());

            return array('success' => false, 'message' => 'Xóa thất bại');
        }
    }
}
