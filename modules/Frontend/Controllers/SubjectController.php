<?php

namespace Modules\Frontend\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Core\Efy\Date\DateHelper;
use Modules\Core\Efy\Library;
use Modules\Frontend\Models\Admin\ListModel;
use Modules\Frontend\Models\Admin\ListtypeModel;
use Modules\Frontend\Services\ExamService;
use Modules\Frontend\Services\SubjectService;
use Modules\Core\Helpers\ForgetPassWordMailHelper;
use Modules\Frontend\Services\Admin\ListService;

class SubjectController extends Controller
{
    private $subjectService;
    private $examService;
    private $listService;

    public function __construct(
        SubjectService $s,
        ExamService $e,
        ListService $l
    ) {
        $this->examService = $e;
        $this->subjectService = $s;
        $this->listService = $l;
    }

    /**
     * Màn hình index
     * 
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\View\View
     */
    public function index(Request $request): View
    {
        $objLibrary           = new Library();
        $arrResult            = array();
        $arrResult            = $objLibrary->_getAllFileJavaScriptCssArray('js', 'frontend/account/info.js', ',', $arrResult);
        $data['stringJsCss']  = json_encode($arrResult);
        $getUser              = $this->subjectService->where('id', $_SESSION["hoithicchc"]['id'])->first();
        $data['dataUnitUser'] = $getUser['cap_don_vi'];

        return view('Frontend::account.info', $data);
    }

    /**
     * Cập nhật thông tin tài khoản
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $input = $request->all();
        $arr = [
            'cap_don_vi' => $input['cap_don_vi'],
            'don_vi' => $input['don_vi'],
        ];
        $updated = $this->subjectService->where('id', $_SESSION["hoithicchc"]['id'])->update($arr);
        if ($updated) {
            $_SESSION["hoithicchc"]["unit"]       = $input['don_vi'];
            $_SESSION["hoithicchc"]["cap_don_vi"] = $input['cap_don_vi'];
        }

        return response()->json(['success' => true, 'message' => 'Cập nhật thành công!']);
    }

    /**
     * Lấy đơn vị
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    // public function getUnit(Request $request)
    // {
    //     $input    = $request->all();
    //     $ListType = ListtypeModel::where('code', 'DM_DON_VI')->first();
    //     $unit     = ListModel::where('system_listtype_id', $ListType->id)->where('xml_data', 'LIKE', '%' . $input['cap_don_vi'] . '%')->get();
    //     $getUser  = $this->subjectService->where('id', $_SESSION["hoithicchc"]['id'])->first();
    //     foreach ($unit as $val) {
    //         $selected = 0;
    //         if (!empty($getUser) && $getUser['don_vi'] == $val['code']) {
    //             $selected = 1;
    //         }
    //         $data['arrUnit'][] = [
    //             "code" => $val['code'],
    //             "name" => $val['name'],
    //             "selected" => $selected
    //         ];
    //     }

    //     return response()->json(['success' => true, 'data' => $data]);
    // }

    /**
     * Lấy đơtj thi
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDotThi(Request $request)
    {
        $input    = $request->all();
        $data['arrDotThi'] = $this->examService->where('nam', $input['nam'])->get();
        return response()->json(['success' => true, 'data' => $data]);
    }

    /**
     * View đổi mật khẩu
     * 
     * @param \Illuminate\Contracts\View\View
     */
    public function changePassword(): View
    {
        $objLibrary          = new Library();
        $arrResult           = array();
        $arrResult           = $objLibrary->_getAllFileJavaScriptCssArray('js', 'frontend/account/change-password.js', ',', $arrResult);
        $data['stringJsCss'] = json_encode($arrResult);

        return view('Frontend::account.change-password', $data);
    }

    /**
     * Cập nhật mật khẩu
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(Request $request): JsonResponse
    {
        $input = $request->all();
        $data  = $this->subjectService->updatePassword($input);

        return response()->json(['success' => true, 'data' => $data]);
    }


    /**
     * Lịch sử bài thi
     * 
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\View\View
     */
    public function viewExamHistory(Request $request): View
    {
        $objLibrary = new Library();
        $arrResult = array();
        $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('js', 'frontend/account/history.js', ',', $arrResult);
        $data['stringJsCss'] = json_encode($arrResult);
        $data['arrYear'] = $this->listService->getByListtypeCode('DM_YEAR', [], ['system_list.code' => 'desc']);

        return view('Frontend::account.history.index', $data);
    }

    /**
     * Lấy dữ liệu
     * 
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    public function examHistory(Request $request): string
    {
        $data = array();
        $input = $request->input();
        $input['type_order'] = 'thu_tu';
        $input['doi_tuong_id'] = $_SESSION["hoithicchc"]["id"];
        $exams = $this->examService->examBySubject($input);
        $exams->map(function($e) {
            $e->thoi_diem_nop_bai = DateHelper::_date($e->thoi_diem_nop_bai, 'Y-m-d H:i:s.000', 'H:i:s d/m/Y');
        });
        $data['datas'] = $exams;

        return view("Frontend::account.history.loadlist", $data)->render();
    }
    /**
     * info
     *
     * @param Request $request
     * @return View
     */
    public function showExam(string $examId)
    {
        $data = $this->examService->show($examId);
        return view('Frontend::account.show2', $data);
    }
}
