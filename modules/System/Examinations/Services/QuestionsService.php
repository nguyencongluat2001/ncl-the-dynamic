<?php

namespace Modules\System\Examinations\Services;

use Illuminate\Support\Facades\DB;
use Modules\Core\Ncl\Library;
use Modules\Core\Ncl\LoggerHelpers;
use Modules\Core\Ncl\Xml;
use Modules\System\ListType\Models\ListTypeModel;
use Modules\System\ListType\Models\ListModel;
use Modules\System\Examinations\Models\ExaminationsModel;
use Modules\System\Examinations\Repositories\QuestionsRepository;
use Modules\System\Users\Models\UnitModel;
use Str;
use Modules\Core\Ncl\Http\BaseService;
use Carbon\Carbon;
class QuestionsService extends BaseService
{
    private $QuestionsRepository;
    private $logger;

    public function __construct(
        QuestionsRepository $l,
        LoggerHelpers $lg
    ) {
        $this->QuestionsRepository = $l;
        $this->logger = $lg;
        $this->logger->setFileName('System_QuestionsService');
    }
    public function repository()
    {
        return QuestionsRepository::class;
    }


    /**
     * Các dữ liệu khởi tạo màn index
     * 
     */
    public function index($id)
    {
        $objLibrary = new Library();
        $arrResult = array();
        $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('js', 'system/Examinations/JS_Questions.js', ',', $arrResult);
        $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('js', 'assets/chosen/chosen.jquery.min.js,assets/jquery.validate.js', ',', $arrResult);
        $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('css', 'assets/chosen/bootstrap-chosen.css', ',', $arrResult);
        // $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('js', 'assets/chosen/chosen.jquery.min.js,assets/jquery-ui-1.10.4.custom.min.js,assets/jstree/jstree.min.js,assets/jstree/jstreetable.js,System/Examinations/JS_Examinations.js,System/Users/JS_Tree.js,System/Users/JS_TempHtml.js,assets/jquery.validate.js', ',', $arrResult);
        // $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('css', 'assets/chosen/bootstrap-chosen.css,assets/jquery-ui/jquery-ui.min.css,assets/tree/style.min.css', ',', $arrResult);
        $data['stringJsCss'] = json_encode($arrResult);
        // lay loai danh muc
        $data['arrListTypes'] = [];
        $ListType = ListTypeModel::where('code','DM_YEAR')->first();
        $data['arrListYear'] = ListModel::where('system_listtype_id',$ListType->id)->get();
        $data['dot_thi_id'] = $id;

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
        $ExaminationsModel = ExaminationsModel::get();
        // lay danh sach danh muc
        // dd($listtype, $currentPage, $perPage, $search);
        // $input['sort'] = 'thu_tu';
        $input['type_order'] = 'thu_tu';

        $objResult = $this->QuestionsRepository->filter($input);
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
        $data['dot_thi_id'] = $input['dot_thi_id'];
        $stt = $this->QuestionsRepository->where('dot_thi_id',$input['dot_thi_id'])->count();
        $data['thu_tu'] = $stt+1;
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
       $getData = $this->QuestionsRepository->where('id',$input['itemId'])->first();
       $ListType = ListTypeModel::where('code','DM_YEAR')->first();
       $getData['arrListYear'] = ListModel::where('system_listtype_id',$ListType->id)->get();
       $data['dot_thi_id'] = $input['dot_thi_id'];
       return $getData;
    }
     /**
     * show chi tiết
     * 
     * @param array $input
     * @return array
     */
    public function show(array $input)
    {
       $getData = $this->QuestionsRepository->where('id',$input['itemId'])->first();
       $ListType = ListTypeModel::where('code','DM_YEAR')->first();
       $getData['arrListYear'] = ListModel::where('system_listtype_id',$ListType->id)->get();
       $data['dot_thi_id'] = $input['dot_thi_id'];
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
        $plaintext = html_entity_decode($input['ten_cau_hoi']);
        $arrData = [
            'nguoi_tao_id'=> $_SESSION['id'],
            'dot_thi_id'=> $input['dot_thi_id'],
            'ten_cau_hoi'=> $plaintext,
            'dap_an_a'=> $input['dap_an_a'],
            'dap_an_b'=> $input['dap_an_b'],
            'dap_an_c'=> $input['dap_an_c'],
            'dap_an_d'=> $input['dap_an_d'],
            'dap_an_dung'=> $input['dap_an_dung'],
            'trang_thai'=> $trangthai,
            'thu_tu'=> $input['thu_tu'],
            'created_at'=> date('Y-m-d H:i:s'),
            'updated_at'=> date('Y-m-d H:i:s')
        ];
        if($input['id'] != '' && $input['id'] != null){
            $create = $this->QuestionsRepository->where('id',$input['id'])->update($arrData);
        }else{
            $arrData['id'] = (string)Str::uuid();
            $create = $this->QuestionsRepository->create($arrData);
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
                $this->QuestionsRepository->where('id',$id)->delete();
            }
        }
        return array('success' => true, 'message' => 'Xóa thành công');
    }
}
