<?php

namespace Modules\System\Cms\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\System\Cms\Services\CategoriesService;

/**
 * Lớp xử lý quản trị, phân quyền người sử dụng
 *
 * @author Toanph <skype: toanph155>
 */
class CategoriesController extends Controller
{
    public function __construct(
        CategoriesService $categoriesService
    ){
        $this->categoriesService = $categoriesService;
    }
    /**
     * Trang Index
     * @return view
     */
    public function index(Request $request)
    {
        $data = $this->categoriesService->index();
        return view('Cms::categories.index', $data);
    }
    /**
     * Danh sách
     * @return view
     */
    public function loadList(Request $request)
    {
        $data = $this->categoriesService->loadList($request->all());
        return view('Cms::categories.loadList', $data);
    }
    /**
     * Thêm mới
     * @return view
     */
    public function create(Request $request)
    {
        $data = $this->categoriesService->create($request->all());
        return view('Cms::categories.add', $data);
    }
    /**
     * Sửa
     * @return view
     */
    public function edit(Request $request)
    {
        $data = $this->categoriesService->_edit($request->all());
        return view('Cms::categories.add', $data);
    }
    /**
     * Thêm danh mục
     */
    public function addList(Request $request)
    {
        $data = $this->categoriesService->addList($request->all());
        return view('Cms::categories.addList', $data);
    }
    /**
     * Cập nhật
     * @return Response
     */
    public function saveList(Request $request)
    {
        $data = $this->categoriesService->saveList($request->all());
        return response()->json($data);
    }
    /**
     * Cập nhật
     * @return Response
     */
    public function update(Request $request)
    {
        $data = $this->categoriesService->_update($request->all());
        return response()->json($data);
    }
    /**
     * Xóa
     * 
     * @param Request $request
     * @return Response
     */
    public function delete(Request $request)
    {
        $data = $this->categoriesService->_delete($request->input());
        return response()->json($data);
    }

}
