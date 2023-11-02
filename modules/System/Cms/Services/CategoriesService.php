<?php

namespace Modules\System\Cms\Services;

use Modules\Api\Services\Admin\ListService;
use Modules\Api\Services\Admin\ListTypeService;
use Modules\Core\Efy\Http\BaseService;
use Modules\Core\Efy\Library;
use Modules\Core\Efy\LoggerHelpers;
use Modules\System\Cms\Repositories\CategoriesRepository;
use Modules\System\Listtype\Helpers\ListtypeHelper;
use Modules\System\Listtype\Models\ListModel;
use Modules\System\Listtype\Models\ListtypeModel;

class CategoriesService extends BaseService
{
    private $logger;
    private $listtypeService;
    private $listService;

    public function __construct(LoggerHelpers $l, ListTypeService $listtypeService, ListService $listService)
    {
        $this->listtypeService = $listtypeService;
        $this->listService     = $listService;
        $this->logger          = $l;
        $this->logger->setFileName('System_CategoriesService');
        parent::__construct();
    }
    public function repository()
    {
        return CategoriesRepository::class;
    }
    /**
     * Lấy dữ liệu màn index
     * 
     * @return array
     */
    public function index(): array
    {
        $objLibrary = new Library;
        $arrResult = array();
        $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('js', 'assets/chosen/chosen.jquery.min.js,assets/jquery-ui-1.10.4.custom.min.js,assets/jstree/jstree.min.js,assets/jstree/jstreetable.js,System/Cms/Js_Categories.js,System/Cms/JS_Tree.js,System/Cms/JS_TempHtml.js,assets/jquery.validate.js', ',', $arrResult);
        $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('css', 'assets/chosen/bootstrap-chosen.css,assets/jquery-ui/jquery-ui.min.css,assets/tree/style.min.css', ',', $arrResult);
        $data['strJsCss'] = json_encode($arrResult);
        $categories_root = $this->repository->select('*')->whereNull('parent_id')->first();
        $data['id_root'] = $categories_root->id ?? '';
        return $data;
    }
    /**
     * Danh sách
     */
    public function loadList($input)
    {
        
    }
    /**
     * Thêm mới
     */
    public function create($input): array
    {
        $listtypeHelper = new ListtypeHelper;
        $data['parent_id'] = $input['id'] ?? '';
        $data['checked'] = 'checked';
        $data['layouts'] = $listtypeHelper->_GetAllListObjectByListCode('DM_LAYOUT');
        $data['category_type'] = $listtypeHelper->_GetAllListObjectByListCode('DM_LOAI_CHUYEN_MUC');
        $p_arr_item_value = $this->repository->select('*')->where('id', $data['parent_id'])->first();
        $data['unitparent'] = $p_arr_item_value->name ?? '';
        $data['order'] = $this->repository->select('*')->where('parent_id', $input['id'])->count() + 1;
        return $data;
    }
    /**
     * Sửa
     */
    public function _edit($input): array
    {
        $listtypeHelper = new ListtypeHelper;
        $categories = $this->repository->where('id', $input['itemId'])->first();
        $data['id'] = $categories->id;
        $data['parent_id'] = $categories->parent_id;
        $data['checked'] = $categories->status == 1 ? 'checked' : '';
        $data['data'] = $categories;
        $data['layouts'] = $listtypeHelper->_GetAllListObjectByListCode('DM_LAYOUT');
        $data['category_type'] = $listtypeHelper->_GetAllListObjectByListCode('DM_LOAI_CHUYEN_MUC');
        $data['unitparent'] = $this->repository->where('id', $categories->parent_id)->first()->name ?? 'CHUYÊN MỤC GỐC';
        return $data;
    }
    /**
     * Màn hình thêm mới loại danh mục
     * @return array
     */
    public function addList($input): array
    {
        $listtype = $this->listtypeService->where('code', $input['code'])->first();
        if(empty($listtype)){
            $listtype = $this->listtypeService->create([
                'code' => $input['code'],
                'name' => $input['name'],
                'xml_file_name' => 'All',
                'order' => $this->listtypeService->select('id')->count() + 1,
                'owner_code_list' => '',
                'status' => 1,
            ]);
        }
        $data['listtype'] = $listtype;
        $data['order'] = $this->listService->where('system_listtype_id', $listtype->id)->count() + 1;
        return $data;
    }
    /**
     * Cập nhật danh mục
     * @return array
     */
    public function saveList($input)
    {
        if(!isset($input['listtype_id']) || (isset($input['listtype_id']) && empty($input['listtype_id']))){
            return array('success' => false, 'message' => 'Không tồn tại danh mục!');
        }
        $list = $this->listService->create([
            'system_listtype_id' => $input['listtype_id'],
            'code' => $input['code'],
            'name' => $input['name'],
            'order' => $input['order'] ?? $this->listService->where('system_listtype_id', $input['listtype_id'])->count() + 1,
            'status' => 1,
            'owner_code_list' => 'All',
            'xml_data' => '<root><data_list><note_list></note_list></data_list></root>',
        ]);
        return array('success' => true, 'data' => $list);
    }
    /**
     * Cập nhật
     * 
     * @param array $input
     * @return array
     */
    public function _update(array $input): array
    {
        // dd($input);
        if(empty($input['id']) && $this->repository->where('id_menu', $input['id_menu'])->exists()){
            return array('success' => false, 'message' => 'Mã chuyên mục đã tồn tại, vui lòng nhập mã khác!');
        }
        $this->logger->setChannel('UPDATE_CATEGORY');
        \DB::beginTransaction();
        try{
            $this->logger->log('Params', $input);
            $data = $this->repository->_update($input);
            \DB::commit();
            return array('success' => true, 'message' => 'Cập nhật thành công', 'parent_id' => $input['parent_id']);
        } catch(\Exception $e){
            dd($e);
            \DB::rollBack();
            $this->logger->log('Exception: ' . $e->getMessage() . ' on file ' . $e->getFile() . ' in line ' . $e->getLine());
            return array('success' => false, 'message' => 'Cập nhật thất bại!');
        }
    }
    /**
     * Xóa
     */
    public function _delete($input): array
    {
        $id = $input['itemId'];
        $categories = $this->repository->where('id', $id)->first();
        $this->repository->_delete($categories);
        return array('success' => true, 'message' => 'Xóa thành công', 'parent_id' => $categories->parent_id);
    }
}