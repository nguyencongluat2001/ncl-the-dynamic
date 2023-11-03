<?php

namespace Modules\System\Cms\Services;

use Carbon\Carbon;
use Modules\Core\Ncl\Http\BaseService;
use Modules\Core\Ncl\Library;
use Modules\Core\Ncl\LoggerHelpers;
use Modules\System\Cms\Repositories\ArticlesRepository;
use Modules\System\Listtype\Helpers\ListtypeHelper;
use Modules\System\Users\Services\UserService;

class ArticlesService extends BaseService
{
    private $categoriesService;
    private $userService;
    private $fileService;

    public function __construct(
        CategoriesService $categoriesService,
        UserService $userService,
        FileService $fileService,
        LoggerHelpers $l
    ){
        $this->categoriesService = $categoriesService;
        $this->userService = $userService;
        $this->fileService = $fileService;
        $this->logger = $l;
        $this->logger->setFileName('System_ArticlesService');
        parent::__construct();
    }
    public function repository()
    {
        return ArticlesRepository::class;
    }
    /**
     * Trang index
     * @return array
     */
    public function index(): array
    {
        $obj = new Library();
        $listtypeHelper = new ListtypeHelper;
        $result = array();
        $result = $obj->_getAllFileJavaScriptCssArray('js', 'system/Cms/JS_Articles.js', ',', $result, 'JS_Articles.min.js');
        $result = $obj->_getAllFileJavaScriptCssArray('js', 'assets/jstree/jstree.min.js,assets/jstree/jstreetable.js,assets/datepicker/bootstrap-datepicker.min.js,assets/datepicker/bootstrap-datepicker.vi.js', ',', $result);
        $result = $obj->_getAllFileJavaScriptCssArray('js', 'assets/jquery.autocomplete.js', ',', $result);
        $result = $obj->_getAllFileJavaScriptCssArray('js', 'assets/chosen/chosen.jquery.min.js', ',', $result);
        $result = $obj->_getAllFileJavaScriptCssArray('js', 'assets/ckeditor/ckeditor.js', ',', $result);
        $result = $obj->_getAllFileJavaScriptCssArray('css', 'assets/tree/style.min.css', ',', $result);
        $result = $obj->_getAllFileJavaScriptCssArray('css', 'assets/chosen/bootstrap-chosen.css', ',', $result);
        $result = $obj->_getAllFileJavaScriptCssArray('css', 'assets/jquery.autocomplete.css', ',', $result);
        $data['stringJsCss'] = json_encode($result);
        $data['arrCategory'] = $this->categoriesService->select('*')->whereNotNull('parent_id')->get();
        $data['arrLoaiTinBai'] = $listtypeHelper->_GetAllListObjectByListCode('DM_LOAI_TIN_BAI');
        $data['arrLoaiTinBai'] = $listtypeHelper->_GetAllListObjectByListCode('DM_TRANG_THAI_TIN_BAI');
        return $data;
    }
    /**
     * Danh sách
     * @param $input Dữ liệu truyền vào
     * @return array $data
     */
    public function loadList($input)
    {
        $input['sort'] = 'order';
        $input['sortType'] = 1;
        $data['datas'] = $this->repository->filter($input);
        return $data;
    }
    /**
     * Màn hình Thêm mới
     * @param $input Dữ liệu truyền vào
     * @return array
     */
    public function _create($input): array
    {
        // dd($_SESSION);
        $data = [];
        $listtypeHelper = new ListtypeHelper;
        $shtmlTree = $this->gennerateTreeCategories('', $_SESSION['OWNER_CODE'] ?? '');
        $data['shtmlTree'] = $shtmlTree;
        $arrTrangThaiTinBai = $listtypeHelper->_GetAllListObjectByListCode('DM_TRANG_THAI_TIN_BAI');
        if (isset($_SESSION['role']) && $_SESSION['role'] == 'USER') {
            $arrTrangThaiTinBai = $this->listService->where('listtype_id', $this->listtypeService->where('code', 'DM_TRANG_THAI_TIN_BAI')->first()->id)->orderBy('order')->get()->toArray();
        }
        $arrAuthor = $this->repository->select('author')->distinct()->get();
        $arrAuthorReturn = array();
        foreach ($arrAuthor as $key => $v) {
            array_push($arrAuthorReturn, $v->author);
        }
        $data['arrAuthorReturn'] = $arrAuthorReturn;
        $data['arrLoaiTinBai'] = $listtypeHelper->_GetAllListObjectByListCode('DM_LOAI_TIN_BAI');
        $data['arrTrangThaiTinBai'] = $arrTrangThaiTinBai;
        $data['role'] = $_SESSION['role'] ?? '';
        $data['trangthaitinbai'] = 'CHO_DUYET';
        $data['checked'] = 'checked';
        return $data;
    }
    /**
     * Màn hình Sửa
     * @param $input Dữ liệu truyền vào
     * @return array
     */
    public function _edit($input): array
    {
        // dd($input);
        $articles = $this->repository->where('id', $input['chk_item_id'])->first();
        $data['datas'] = $articles;
        $shtmlTree = $this->gennerateTreeCategories($articles->categories_id, $_SESSION['OWNER_CODE'] ?? '');
        $data['shtmlTree'] = $shtmlTree;
        $arrAuthor = $this->repository->select('author')->distinct()->get();
        $arrAuthorReturn = array();
        foreach ($arrAuthor as $key => $v) {
            array_push($arrAuthorReturn, $v->author);
        }
        $data['arrAuthorReturn'] = $arrAuthorReturn;
        $data['role'] = $_SESSION['role'] ?? '';
        $data['trangthaitinbai'] = $articles->status_articles ?? 'CHO_DUYET';
        $listtypeHelper = new ListtypeHelper;
        $arrTrangThaiTinBai = $listtypeHelper->_GetAllListObjectByListCode('DM_TRANG_THAI_TIN_BAI');
        if (isset($_SESSION['role']) && $_SESSION['role'] == 'USER') {
            $arrTrangThaiTinBai = $this->listService->where('listtype_id', $this->listtypeService->where('code', 'DM_TRANG_THAI_TIN_BAI')->first()->id)->orderBy('order')->get()->toArray();
        }
        $data['arrLoaiTinBai'] = $listtypeHelper->_GetAllListObjectByListCode('DM_LOAI_TIN_BAI');
        $data['arrTrangThaiTinBai'] = $arrTrangThaiTinBai;
        $data['feature_img_base'] = $articles->feature_img;
        $data['arrAttachments'] = $this->fileService->where('articles_id', $input['chk_item_id'])->get();
        return $data;
    }
    /**
     * Load Chuyên mục khi thêm mới
     * @param $list_fkcategories danh sách chuyên mục
     * @param $ownercode Mã đơn vị
     * @return string
     */
    public function gennerateTreeCategories($list_fkcategories = '', $ownercode): string
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] == 'USER') {
            $role = str_replace(',', "','", trim($_SESSION['PERMISSION_CMS']['C_LIST_CATEGORY_SOAN_TIN'], ','));
            $role_cms = str_replace("'',", '', $role);
            $sql = "SELECT (case when id in (select distinct parent_id from chuyen_muc) then 0 else 1 end) as lastItem,id,parent_id,name,slug FROM chuyen_muc WHERE parent_id  = (SELECT id FROM chuyen_muc WHERE parent_id IS NULL) AND category_type ='CHUYEN_MUC_BAI_VIET' AND owner_code = '$ownercode' AND (id IN ('$role_cms') OR  id in (
                select distinct parent_id from chuyen_muc where  id in ('$role_cms')) ) AND  status ='1' ORDER BY order";
            $arrCateRoot = \DB::select($sql);
        } else {
            // $sql = "SELECT *, (case when id in (select distinct parent_id from chuyen_muc) then 0 else 1 end) as lastItem from chuyen_muc where parent_id = (select id from chuyen_muc where parent_id is null) AND category_type ='CHUYEN_MUC_BAI_VIET' AND owner_code = '$ownercode' order by `order`";
            $arrCateRoot = $this->categoriesService->select('*', \DB::raw("(case when id in (select distinct parent_id from chuyen_muc) then 0 else 1 end) as lastItem"))
                ->where('parent_id', $this->categoriesService->select('id')->whereNull('parent_id')->first()->id)
                ->where('category_type', 'CHUYEN_MUC_BAI_VIET')->where('status', 1)->orderBy('order')->get();
        }
        $shtmlTree = '<ul>';
        foreach ($arrCateRoot as $key => $value) {
            $selected = "";
            if (strpos("Ncl_" . $list_fkcategories, $value->id) > 0) {
                $selected = "true";
            }
            if ($value->lastItem == '0') {
                $disabled = "\"disabled\":\"0\"";
            } else {
                $disabled = "\"\":\"\"";
            }
            $shtmlTree .= "<li id='" . $value->id . "' data-jstree='{ \"icon\":\"fa fa fa-university\", $disabled ,\"state\" : { \"checkbox_disabled\" : true },\"selected\":\"$selected\" }'  slug=\"" . $value->slug . "\" is_last_item=\"" . $value->lastItem . "\">" . $value->name;
            if ($value->lastItem == '0') {
                if (isset($_SESSION['role']) && $_SESSION['role'] == 'USER') {
                    $role = str_replace(',', "','", trim($_SESSION['PERMISSION_CMS']['C_LIST_CATEGORY_SOAN_TIN'], ','));
                    $role_cms = str_replace("'',", '', $role);
                    $sql = "SELECT (case when id in (select distinct parent_id from chuyen_muc) then 0 else 1 end) as lastItem,id,parent_id,name,slug from chuyen_muc WHERE parent_id  = '" . $value->id . "' AND category_type ='CHUYEN_MUC_BAI_VIET' AND owner_code = '$ownercode' AND (parent_id in ('$role_cms') or id in ('$role_cms')) AND status ='1' ORDER BY order";
                    $arrCategoriesCap1 = \DB::select($sql);
                } else {
                    $arrCategoriesCap1 = $this->categoriesService->select('*', \DB::raw("(case when id in (select distinct parent_id from chuyen_muc) then 0 else 1 end) as lastItem"))
                        ->where('parent_id', $value->id)->where('category_type', 'CHUYEN_MUC_BAI_VIET')
                        ->where('status', 1)->orderBy('order')->get();
                }
                $shtmlTree .= "<ul>";
                foreach ($arrCategoriesCap1 as $key1 => $value1) {
                    $selected = "";
                    if (strpos("Ncl_" . $list_fkcategories, $value1->id) > 0) {
                        $selected = "true";
                    }
                    $shtmlTree .= "<li id='" . $value1->id . "' data-jstree='{ \"icon\":\"fas fa-newspaper\" ,\"selected\":\"$selected\" }' slug=\"" . $value1->slug . "\" is_last_item=\"" . $value1->lastItem . "\">";
                    $shtmlTree .= $value1->name;
                    if ($value1->lastItem == '0') {
                        if (isset($_SESSION['role']) && $_SESSION['role'] == 'USER') {
                            $role = str_replace(',', "','", trim($_SESSION['PERMISSION_CMS']['C_LIST_CATEGORY_SOAN_TIN'], ','));
                            $role_cms = str_replace("'',", '', $role);
                            $sql = "SELECT (case when id in (select distinct parent_id from chuyen_muc) then 0 else 1 end) as lastItem,id,parent_id,name,slug from chuyen_muc WHERE parent_id  = '" . $value1->id . "' AND category_type ='CHUYEN_MUC_BAI_VIET' AND owner_code = '$ownercode' AND parent_id in ('$role_cms')  AND  status ='1' ORDER BY order";
                            $arrCategoriesCap2 = \DB::select($sql);
                        } else {
                            $arrCategoriesCap2 = $this->categoriesService->select('*', \DB::raw("(case when id in (select distinct parent_id from chuyen_muc) then 0 else 1 end) as lastItem"))
                                ->where('parent_id', $value->id)->where('category_type', 'CHUYEN_MUC_BAI_VIET')
                                ->where('status', 1)->orderBy('order')->get();
                        }
                        $shtmlTree .= "<ul>";
                        foreach ($arrCategoriesCap2 as $key2 => $value2) {
                            $selected = "";
                            if (strpos("Ncl_" . $list_fkcategories, $value2->id) > 0) {
                                $selected = "true";
                            }
                            $shtmlTree .= "<li id='" . $value2->id . "' data-jstree='{ \"icon\":\"fas fa-newspaper\" ,\"selected\":\"$selected\" }' slug=\"" . $value2->slug . "\" is_last_item=\"" . $value2->lastItem . "\" >";
                            $shtmlTree .= $value2->name;
                        }
                        $shtmlTree .= "</ul>";
                    }
                    $shtmlTree .= "</li>";
                }
                $shtmlTree .= "</ul>";
            }
            $shtmlTree .= "</li>";
        }
        $shtmlTree .= "</ul>";
        return $shtmlTree;
    }
    /**
     * Cập nhật
     */
    public function _update($input)
    {
        $this->logger->setChannel('UPDATE_CATEGORY');
        \DB::beginTransaction();
        parse_str($input['data'], $params);
        $params['create_date'] = $params['create_date'] != '' ? Carbon::createFromFormat('d/m/Y', $params['create_date'])->format('Y-m-d') : null;
        $params['categories_id'] = $input['categories_id'];
        $params['content'] = $input['content'];
        $params['owner_code'] = $input['owner_code'];
        $users = $this->userService->where('id', $_SESSION['id'])->first();
        $params['users_id'] = $users->id ?? null;
        $params['users_name'] = $users->name ?? null;
        $files = $this->saveFiles($_FILES['choose_img'] ?? []);
        $params['fileUpload'] = $files;
        try {
            $this->logger->log('Params', $params);
            $data = $this->repository->_update($params);
            $attachments = $this->saveAttachments($data->id, $_FILES ?? []);
            \DB::commit();
            return array('success' => true, 'message' => 'Cập nhật thành công');
        } catch (\Exception $e) {
            dd($e);
            \DB::rollBack();
            $this->logger->log('Exception: ' . $e->getMessage() . ' on file ' . $e->getFile() . ' in line ' . $e->getLine());
            return array('success' => false, 'message' => 'Cập nhật thất bại!');
        }
    }
    /**
     * Lưu file
     */
    public function saveFiles($files)
    {
        $sDir = public_path('attach-file') . chr(92);
        $folder = Library::_createFolder($sDir, date('Y'), date('m'), date('d'));
        $result = [];
        if ($files != []) {
            $filename = $files['name'];
            $filename = Library::_convertVNtoEN($filename);
            $filename = Library::_replaceBadChar($filename);
            $fullname = date("Y_m_d_His") . rand(1000, 9999) . '!~!' . $filename;
            $fullfilename = $folder . '/' . $fullname;
            copy($files['tmp_name'], $fullfilename);
            $result = [
                'url' => url('public/attach-file') . '/' . date('Y/m/d') . '/' . $fullname,
                'file_name' => $fullname,
            ];
        }
        return $result;
    }
    /**
     * Lưu file đính kèm
     */
    public function saveAttachments($id, $files)
    {
        $return = [];
        if ($files != []) {
            $i = 0;
            foreach ($files as $key => $file) {
                if ($key != 'choose_img') {
                    $arrFile = $this->fileService->upload($file);
                    $params = [
                        'id' => (string)\Str::uuid(),
                        'articles_id' => $id,
                        'file_name' => $arrFile['baseUrl']['fileName'] ?? '',
                        'file_url' => $arrFile['baseUrl']['url'] ?? '',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                    $this->fileService->insert($params);
                    $return[$i++] = $arrFile;
                }
            }
        }
        return $return;
    }
    /**
     * Xóa
     */
    public function _delete($input): array
    {
        $idlist = $input['listitem'];
        $arrList = explode(',', trim($idlist, ','));
        foreach ($arrList as $val) {
            $articlesModel = $this->repository->where('id', $val)->first();
            $ROLE = $_SESSION['role'] ?? '';
            if ($ROLE == 'USER') {
                $arrCategory = explode(',', trim($_SESSION['PERMISSION_CMS']['C_LIST_CATEGORY_XOA'], ','));
                if (in_array($articlesModel->categories_id, $arrCategory) == false) {
                    return array('danger' => true, 'message' => 'Bạn không có quyền xóa tin này');
                }
            }
            $articlesModel->delete();
        }
        return array('success' => true, 'message' => 'Xóa thành công');
    }
    /**
     * Xem bài viết
     */
    public function see($input): array
    {
        // dd($input);
        $articles = $this->repository->where('id', $input['itemId'])->select(["*", \DB::raw("format(create_date,'dd/MM/yyyy') as create_date")])->first();
        $ROLE = $_SESSION['role'] ?? '';
        if ($ROLE == 'USER') {
            $arrCategory = explode(',', trim($_SESSION['PERMISSION_CMS']['C_LIST_CATEGORY_XEM'], ','));
            if (in_array($articles->categories_id, $arrCategory) == false) {
                return array('danger' => true, 'message' => 'Bạn không có quyền xem tin này');
            }
        }
        $arr = [];
        if(!empty($articles)){
            $files = $this->fileService->where('articles_id', $articles->id)->get();
            foreach($files as $key => $file){
                $file_name = explode('!~!', $file->file_name);
                $arr[$key] = [
                    'file_name' => $file_name[1] ?? ($file->file_name ?? ''),
                    'url' => $file->file_url ?? '',
                ];
            }
        }
        $data['action'] = 'xem';
        $data['breadcrumb'] = 'XEM BÀI VIẾT';
        $data['datas'] = $articles;
        $data['files'] = $arr;
        return $data;
    }
    /**
     * Xóa file
     */
    public function deletefile($input)
    {
        $arrFile = json_decode($input['filename'], true);
        $arrFileName = explode('!~!', $arrFile['file_name']);
        $arrFolder = explode('_', $arrFileName[0]);
        $folder = $arrFolder[0] . '/' . $arrFolder[1] . '/' . $arrFolder[2];
        if (file_exists(base_path('public/attach-file') . '/' . $folder . '/' . $arrFile['file_name'])) {
            unlink(base_path('public/attach-file') . '/' . $folder . '/' . $arrFile['file_name']);
        }
        $files = $this->fileService->where('id', $arrFile['id'])->delete();
        return array('FileData' => [
            'id' => $input['id'],
            'filename' => $input['filename'],
        ],);
    }
}