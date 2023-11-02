<?php

namespace Modules\System\Listtype\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\System\Listtype\Services\ListtypeService;

/**
 * Controler xử lý về loại danh mục.
 *
 * @author Toanph <skype: toanph1505>
 */
class ListtypeController extends Controller
{
    private $listtypeService;

    public function __construct(ListtypeService $l)
    {
        $this->listtypeService = $l;
    }

    /**
     * Load các file js, css của đối tượng
     *
     * @return view
     */
    public function index()
    {
        $data = $this->listtypeService->index();
        return view('Listtype::Listtype.index', $data);
    }

    /**
     * Lấy danh sách danh mục
     *
     * @param Request $request
     * @return string json
     */
    public function loadList(Request $request)
    {
        $result = $this->listtypeService->loadList($request->input());
        return response()->json($result);
    }

    /**
     * View Thêm mới một danh mục
     *
     * @param Request $request
     * @return View
     */
    public function add(Request $request)
    {
        $data = $this->listtypeService->create($request->input());
        return view('Listtype::listtype.add', $data);
    }

    /**
     * Hiệu chỉnh một loại danh mục
     *
     * @param Request $request
     * @return view
     */
    public function edit(Request $request)
    {
        $data = $this->listtypeService->edit($request->input());
        return view('Listtype::Listtype.add', $data);
    }

    /**
     * Cập nhật một loại danh mục
     *
     * @param Request $request
     * @return string json
     */
    public function update(Request $request)
    {
        $result = $this->listtypeService->update($request->input());
        return response()->json($result);
    }

    /**
     * Cập nhật một loại danh mục
     *
     * @param Request $request
     * @return string json
     */
    public function delete(Request $request)
    {
        $result = $this->listtypeService->delete($request->listitem);
        return response()->json($result);
    }
}
