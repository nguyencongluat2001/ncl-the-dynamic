<?php

namespace Modules\System\Objects\Services;

use Illuminate\Support\Facades\DB;
use Modules\Core\Ncl\Library;
use Modules\Core\Ncl\LoggerHelpers;
use Modules\Core\Ncl\Xml;
use Modules\System\ListType\Models\ListTypeModel;
use Modules\System\ListType\Models\ListModel;
use Modules\System\Objects\Models\ObjectsModel;
use Modules\System\Objects\Repositories\ObjectsRepository;
use Modules\System\Users\Models\UnitModel;
use Str;
use Modules\Core\Ncl\Http\BaseService;
use Carbon\Carbon;
class ObjectsService extends BaseService
{
    private $ObjectsRepository;
    private $logger;

    public function __construct(
        ObjectsRepository $l,
        LoggerHelpers $lg
    ) {
        parent::__construct();
        $this->ObjectsRepository = $l;
        $this->logger = $lg;
        $this->logger->setFileName('System_ObjectsService');
    }
    public function repository()
    {
        return ObjectsRepository::class;
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
        $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('js', 'system/Objects/JS_Objects.js', ',', $arrResult);
        $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('js', 'assets/chosen/chosen.jquery.min.js,assets/jquery.validate.js', ',', $arrResult);
        $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('css', 'assets/chosen/bootstrap-chosen.css', ',', $arrResult);
        $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('js', 'assets/chosen/chosen.jquery.min.js,assets/jquery-ui-1.10.4.custom.min.js,assets/jstree/jstree.min.js,assets/jstree/jstreetable.js,System/Objects/JS_Objects.js,System/Users/JS_Tree.js,System/Users/JS_TempHtml.js,assets/jquery.validate.js', ',', $arrResult);
        $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('css', 'assets/chosen/bootstrap-chosen.css,assets/jquery-ui/jquery-ui.min.css,assets/tree/style.min.css', ',', $arrResult);
        $data['stringJsCss'] = json_encode($arrResult);
        // lay loai danh muc
        $data['arrListTypes'] = [];
        $ListType = ListTypeModel::where('code','DM_YEAR')->first();
        $data['arrListYear'] = ListModel::where('system_listtype_id',$ListType->id)->get();
        $data['ngay_bat_dau'] = '01-01-'.date('Y');
        $data['ngay_ket_thuc'] = date('d-m-Y');

        return $data;
    }

    /**
     * Lấy dữ liệu
     * 
     * @param array $input
     */
    public function loadList($input)
    {
        $currentPage = $input['currentPage'];
        $perPage = $input['perPage'];
        $search = $input['search'];
        $listtype = $input['listtype'] ?? '';
        $ObjectsModel = ObjectsModel::get();
        // lay danh sach danh muc
        // dd($listtype, $currentPage, $perPage, $search);
        // dd($input);
        $input['ngay_bat_dau'] = Carbon::createFromFormat('d/m/Y', $input['ngay_bat_dau'])->format('Y-m-d');
        $input['ngay_ket_thuc'] = Carbon::createFromFormat('d/m/Y', $input['ngay_ket_thuc'])->format('Y-m-d');
        $objResult = $this->ObjectsRepository->filter($input);
        $data['datas'] = $objResult;
        return $data; 

        // return array(
        //     'Dataloadlist' => $objResult,
        //     'pagination'   => (string) $objResult->links('pagination.default'),
        //     'perPage'      => $perPage,
        //     // 'xmlFilePath'  => $xmlFilePath
        // );
    }

    /**
     * Dữ liệu form thêm mới
     * 
     * @param array $input
     * @return array
     */
    public function create($input)
    {
        $ListType = ListTypeModel::where('code','DM_YEAR')->first();
        $data['arrListYear'] = ListModel::where('system_listtype_id',$ListType->id)->get();
        return $data;
    }

    /**
     * Dữ liệu form sửa
     * 
     * @param array $input
     * @return array
     */
    public function edit(array $input)
    {
       $getData = $this->ObjectsRepository->where('id',$input['itemId'])->first();
       $ListType = ListTypeModel::where('code','DM_YEAR')->first();
       $getData['arrListYear'] = ListModel::where('system_listtype_id',$ListType->id)->get();
       return $getData;
    }

    /**
     * Update hoặc thêm mới
     * 
     * @param array $input
     * @return 
     */
    public function updateData($input)
    {
        $trangthai = 0;
        // $ngay_bat_dau = Carbon::createFromFormat('d/m/Y', $input['ngay_bat_dau'])->format('Y-m-d');
        // $ngay_ket_thuc = Carbon::createFromFormat('d/m/Y', $input['ngay_ket_thuc'])->format('Y-m-d');
        if(!empty($input['trang_thai']) && $input['trang_thai'] == 'on'){
            $trangthai = 1;
        }
        $arrData = [
            'nguoi_tao_id'=> $_SESSION['id'],
            'ten'=> $input['ten'],
            'nam'=> $input['nam'],
            'ngay_bat_dau'=> $input['ngay_bat_dau'],
            'ngay_ket_thuc'=> $input['ngay_bat_dau'],
            'trang_thai'=> $trangthai,
            'thoi_gian_lam_bai'=> $input['thoi_gian_lam_bai'],
            'created_at'=> date('Y-m-d H:i:s'),
            'updated_at'=> date('Y-m-d H:i:s')
        ];
        if($input['id'] != '' && $input['id'] != null){
            $create = $this->ObjectsRepository->where('id',$input['id'])->update($arrData);
        }else{
            $arrData['id'] = (string)Str::uuid();
            $create = $this->ObjectsRepository->create($arrData);
        }
        return array('success' => true, 'message' => 'Cập nhật thành công');

    }

    /**
     * Xóa
     * 
     * @param string $listitem Danh sách các đối tượng cần xóa
     * @return array
     */
    public function deletes(string $listitem): array
    {
        $listids = trim($listitem, ",");
        $ids = explode(",", $listids);
        foreach ($ids as $id) {
            if ($id) {
                $this->ObjectsRepository->where('id',$id)->delete();
            }
        }
        return array('success' => true, 'message' => 'Xóa thành công');
    }
     /**
     * show chi tiết
     * 
     * @param array $input
     * @return array
     */
    public function show(array $input)
    {
       $getData = $this->ObjectsRepository->where('id',$input['itemId'])->first();
       return $getData;
    }
}
