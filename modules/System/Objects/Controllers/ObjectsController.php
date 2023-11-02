<?php

namespace Modules\System\Objects\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\System\Objects\Services\ObjectsService;
use Modules\System\ListType\Models\ListTypeModel;
use Modules\System\ListType\Models\ListModel;
use Modules\System\Examinations\Services\ExaminationsService;

/**
 * Controler xử lý về đối tượng danh mục.
 *
 * @author Toanph <skype: toanph1505>
 */
class ObjectsController extends Controller
{
    private $ObjectsService;

    public function __construct(
        ObjectsService $l,
        ExaminationsService $ExaminationsService
    )
    {
        $this->ExaminationsService = $ExaminationsService;
        $this->ObjectsService = $l;
    }

    /**
     * khởi tạo dữ liệu mẫu, Load các file js, css của đối tượng
     *
     * @return View
     */
    public function index()
    {
        $data = $this->ObjectsService->index();
        return view('Objects::List.index', $data);
    }

    /**
     * Lấy danh sách danh mục đối tượng của một danh mục
     *
     * @param Request $request
     * @return Response
     */
    public function loadList(Request $request)
    {
        $result = $this->ObjectsService->loadList($request->input());
        return view("Objects::List.loadlist", $result)->render();
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
        $data['datas'] = $this->ObjectsService->create($request->input());
        return view('Objects::List.add', $data);
    }

    /**
     * Hiệu chỉnh một danh mục đối tượng
     *
     * @param Request $request
     * @return View
     */
    public function edit(Request $request)
    {
        $data['datas'] = $this->ObjectsService->edit($request->input());
        return view('Objects::List.add', $data);
    }

    /**
     * Cập nhật một danh mục đối tượng
     *
     * @param Request $request
     * @return string json
     */
    public function update(Request $request)
    {
        $result = $this->ObjectsService->updateData($request->input());
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
        $result = $this->ObjectsService->deletes($request->listitem);
        return response()->json($result);
    }
       /**
     * Lấy đơn vị
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnit(Request $request)
    {
        $input    = $request->all();
        $ListType = ListTypeModel::where('code', 'DM_DON_VI')->first();
        $unit     = ListModel::where('system_listtype_id', $ListType->id)->where('xml_data', 'LIKE', '%' . $input['cap_don_vi'] . '%')->get();
        $getUser  = $this->ObjectsService->where('id', $_SESSION['id'])->first();
        foreach ($unit as $val) {
            $selected = 0;
            if(!empty($getUser) && $getUser['don_vi'] == $val['code']){
                $selected = 1;
            }
            $data['arrUnit'][] = [
                "code" => $val['code'],
                "name" => $val['name'],
                "selected" => $selected
            ];
        }

        return response()->json(['success' => true, 'data' => $data]);
    }
    /**
     * Lấy đơtj thi
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDotThi(Request $request)
    {
        $input    = $request->all();
        $data['arrDotThi'] = $this->ExaminationsService->where('nam', $input['nam'])->get();
        return response()->json(['success' => true, 'data' => $data]);
    }
    /**
     * Xuat caches
     *
     * @param Request $request
     * @return string json
     */
    // public function exportCache(Request $request)
    // {
    //     if ($this->export($request->Objects) == true) {
    //         return array('success' => true, 'message' => 'Xuất cache thành công');
    //     } else {
    //         return array('success' => false, 'message' => 'Không có dữ liệu');
    //     }
    // }

    // public function export($Objects_id)
    // {
    //     $arrResult = DB::table('E_system_Objects as a')
    //         ->join('t_system_list as b', 'a.id', '=', 'b.Objects_id')
    //         ->where('a.id', $Objects_id)
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
    //         $Objects_code = $arrResult[0]->list_type_code;
    //         Cache::forget($Objects_code);
    //         Cache::forever($Objects_code, $data);
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }
     /**
     * info
     *
     * @param Request $request
     * @return View
     */
    public function show(Request $request)
    {
        $data['datas'] = $this->ObjectsService->show($request->input());
        return view('Objects::List.show', $data);

    }
}
