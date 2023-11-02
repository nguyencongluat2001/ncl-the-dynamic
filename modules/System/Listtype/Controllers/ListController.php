<?php

namespace Modules\System\Listtype\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\System\Listtype\Services\ListService;

/**
 * Controler xử lý về đối tượng danh mục.
 *
 * @author Toanph <skype: toanph1505>
 */
class ListController extends Controller
{
    private $listService;

    public function __construct(ListService $l)
    {
        $this->listService = $l;
    }

    /**
     * khởi tạo dữ liệu mẫu, Load các file js, css của đối tượng
     *
     * @return View
     */
    public function index()
    {
        $data = $this->listService->index();
        return view('Listtype::List.index', $data);
    }

    /**
     * Lấy danh sách danh mục đối tượng của một danh mục
     *
     * @param Request $request
     * @return Response
     */
    public function loadList(Request $request)
    {
        $result = $this->listService->loadList($request->input());
        return response()->json($result);
    }

    /**
     * Thêm mới một loại danh mục đối tượng
     *
     * @param Request $request
     * @return View
     */
    public function add(Request $request)
    {
        $data = $this->listService->create($request->input());
        return view('Listtype::List.add', $data);
    }

    /**
     * Hiệu chỉnh một danh mục đối tượng
     *
     * @param Request $request
     * @return View
     */
    public function edit(Request $request)
    {
        $data = $this->listService->edit($request->input());
        return view('Listtype::List.add', $data);
    }

    /**
     * Cập nhật một danh mục đối tượng
     *
     * @param Request $request
     * @return string json
     */
    public function update(Request $request)
    {
        $result = $this->listService->update($request->input());
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
        $result = $this->listService->delete($request->listitem);
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
    //     if ($this->export($request->listtype) == true) {
    //         return array('success' => true, 'message' => 'Xuất cache thành công');
    //     } else {
    //         return array('success' => false, 'message' => 'Không có dữ liệu');
    //     }
    // }

    // public function export($listtype_id)
    // {
    //     $arrResult = DB::table('t_system_listtype as a')
    //         ->join('t_system_list as b', 'a.id', '=', 'b.listtype_id')
    //         ->where('a.id', $listtype_id)
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
    //         $listtype_code = $arrResult[0]->list_type_code;
    //         Cache::forget($listtype_code);
    //         Cache::forever($listtype_code, $data);
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }
}
