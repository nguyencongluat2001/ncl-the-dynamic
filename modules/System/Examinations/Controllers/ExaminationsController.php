<?php

namespace Modules\System\Examinations\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\System\Examinations\Services\ExaminationsService;

/**
 * Controler xử lý về đối tượng danh mục.
 *
 * @author Toanph <skype: toanph1505>
 */
class ExaminationsController extends Controller
{
    private $ExaminationsService;

    public function __construct(ExaminationsService $l)
    {
        $this->ExaminationsService = $l;
    }

    /**
     * khởi tạo dữ liệu mẫu, Load các file js, css của đối tượng
     *
     * @return View
     */
    public function index()
    {
        $data = $this->ExaminationsService->index();
        return view('Examinations::List.index', $data);
    }

    /**
     * Lấy danh sách danh mục đối tượng của một danh mục
     *
     * @param Request $request
     * @return Response
     */
    public function loadList(Request $request)
    {
        $result = $this->ExaminationsService->loadList($request->input());
        return view("Examinations::List.loadlist", $result)->render();
        // return response()->json($result);
    }

    /**
     * Thêm mới một loại danh mục đối tượng
     *
     * @param Request $request
     * @return View
     */
    public function add(Request $request)
    {
        $data['datas'] = $this->ExaminationsService->create($request->input());
        return view('Examinations::List.add', $data);
    }

    /**
     * Hiệu chỉnh một danh mục đối tượng
     *
     * @param Request $request
     * @return View
     */
    public function edit(Request $request)
    {
        $data['datas'] = $this->ExaminationsService->edit($request->input());
        return view('Examinations::List.add', $data);
    }

    /**
     * Cập nhật một danh mục đối tượng
     *
     * @param Request $request
     * @return string json
     */
    public function update(Request $request)
    {
        $result = $this->ExaminationsService->updateData($request->input());
        return response()->json($result);
    }

    /**
     * Xóa một danh mục đối tượng
     *
     * @param Request $request
     * @return string json
     */
    public function delete(Request $request)
    {
        $result = $this->ExaminationsService->deletes($request->listitem);
        return response()->json($result);
    }

    /**
     * Xuat caches
     *
     * @param Request $request
     * @return string json
     */
    // public function exportCache(Request $request)
    // {
    //     if ($this->export($request->Examinations) == true) {
    //         return array('success' => true, 'message' => 'Xuất cache thành công');
    //     } else {
    //         return array('success' => false, 'message' => 'Không có dữ liệu');
    //     }
    // }

    // public function export($Examinations_id)
    // {
    //     $arrResult = DB::table('E_system_examinations as a')
    //         ->join('t_system_list as b', 'a.id', '=', 'b.Examinations_id')
    //         ->where('a.id', $Examinations_id)
    //         ->select('b.code', 'b.name', 'b.xml_data', 'a.code as list_type_code')
    //         ->get()->toArray();
    //     if ($arrResult) {
    //         $count = sizeof($arrResult);
    //         for ($i = 0; $i < $count; $i++) {
    //             $temp = array();
    //             $temp['code'] = $arrResult[$i]->code;
    //             $temp['name'] = $arrResult[$i]->name;
    //             if ($arrResult[$i]->xml_data != '') {
    //                 $objXml = simplexml_load_string($arrResult[$i]->xml_data);
    //                 $datalist = (array) $objXml->data_list;
    //                 foreach ($datalist as $key => $value) {
    //                     $temp[(string) $key] = (string) $value;
    //                 }
    //             }
    //             $data[] = $temp;
    //         }
    //         $Examinations_code = $arrResult[0]->list_type_code;
    //         Cache::forget($Examinations_code);
    //         Cache::forever($Examinations_code, $data);
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }
}
