<?php

namespace Modules\System\Exams\Services;

use Illuminate\Support\Facades\DB;
use Modules\Core\Efy\Library;
use Modules\Core\Efy\LoggerHelpers;
use Modules\Core\Efy\Xml;
use Modules\System\ListType\Models\ListTypeModel;
use Modules\System\ListType\Models\ListModel;
use Modules\System\Exams\Models\ExamsModel;
use Modules\System\Exams\Repositories\ExamsRepository;
use Modules\System\Users\Models\UnitModel;
use Str;
use Modules\Core\Efy\Http\BaseService;
use Carbon\Carbon;
use Modules\Frontend\Repositories\QuestionRepository;
use Modules\System\Exams\Models\ExamDetailModel;

class ExamsService extends BaseService
{
    private $ExamsRepository;
    private $logger;

    public function __construct(
        ExamsRepository $l,
        LoggerHelpers $lg
    ) {
        $this->ExamsRepository = $l;
        $this->logger = $lg;
        $this->logger->setFileName('System_ExamsService');
    }
    public function repository()
    {
        return ExamsRepository::class;
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
        $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('js', 'system/Exams/JS_Exams.js', ',', $arrResult);
        $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('js', 'assets/chosen/chosen.jquery.min.js,assets/jquery.validate.js', ',', $arrResult);
        $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('css', 'assets/chosen/bootstrap-chosen.css', ',', $arrResult);
        $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('js', 'assets/chosen/chosen.jquery.min.js,assets/jquery-ui-1.10.4.custom.min.js,assets/jstree/jstree.min.js,assets/jstree/jstreetable.js,System/Exams/JS_Exams.js,System/Users/JS_Tree.js,System/Users/JS_TempHtml.js,assets/jquery.validate.js', ',', $arrResult);
        $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('css', 'assets/chosen/bootstrap-chosen.css,assets/jquery-ui/jquery-ui.min.css,assets/tree/style.min.css', ',', $arrResult);
        $data['stringJsCss'] = json_encode($arrResult);
        // lay loai danh muc
        $data['arrListTypes'] = [];
        $ListType = ListTypeModel::where('code','DM_YEAR')->first();
        $data['arrListYear'] = ListModel::where('system_listtype_id',$ListType->id)->get();
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
        $input['type_order'] = 'thu_tu';
        $input['type_order_1'] = 'thu_tu';

        $ExamsModel = ExamsModel::get();
        // lay danh sach danh muc
        // dd($listtype, $currentPage, $perPage, $search);
        $objResult = $this->ExamsRepository->filter($input);
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
       $getData = $this->ExamsRepository->where('id',$input['itemId'])->first();
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
            $create = $this->ExamsRepository->where('id',$input['id'])->update($arrData);
        }else{
            $arrData['id'] = (string)Str::uuid();
            $create = $this->ExamsRepository->create($arrData);
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
                $this->ExamsRepository->where('id',$id)->delete();
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
       $getData = $this->ExamsRepository->where('id',$input['itemId'])->first();
    //    $result = ExamDetailModel::where('bai_thi_id',$getData['id'])->get();
       $fk_vote = $getData['id'];
       $result   = DB::select("
                select a.*,c.ten_cau_hoi,c.dap_an_a,c.dap_an_b,c.dap_an_c,c.dap_an_d
                from bai_thi_chi_tiet a 
                inner join bai_thi b on a.bai_thi_id=b.id
                inner join cau_hoi c on a.cau_hoi_id=c.id
                where a.bai_thi_id='$fk_vote' order by [thu_tu] ASC");
    //    $result = $this->ManageQuestionSipasService
    //                              ->where('survey_sipas_id',$getYear->id)
    //                              ->where('type_vote',$input['type_unit'])
    //                              ->orderBy('order','ASC')
    //                              ->get();
        $i = 0;
        $k = 1;
        foreach ($result as $value) {
            $name_convert_1 = str_replace('<p>','',$value->ten_cau_hoi);
            $name_convert_2 = str_replace('</p>','',$name_convert_1);
            $result[$i]->name_convert = $name_convert_2;

            $listoption_convert = [
                ['c_ques'=> 'A','name' => $value->dap_an_a, 'code' => 'dap_an_a', 'question_id' => $value->bai_thi_id, 'result' => $value->dap_an,],
                ['c_ques'=> 'B','name' => $value->dap_an_b, 'code' => 'dap_an_b', 'question_id' => $value->bai_thi_id, 'result' => $value->dap_an,],
                ['c_ques'=> 'C','name' => $value->dap_an_c, 'code' => 'dap_an_c', 'question_id' => $value->bai_thi_id, 'result' => $value->dap_an,],
                ['c_ques'=> 'D','name' => $value->dap_an_d, 'code' => 'dap_an_d', 'question_id' => $value->bai_thi_id, 'result' => $value->dap_an,],
            ];
            $result[$i]->listoption = ($listoption_convert);
            $i++;
            $k++;
        }
       $getData['question'] = $result;
    //    dd($getData);
       return $getData;
    }
}
