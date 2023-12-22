<?php

namespace Modules\System\Users\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\System\Users\Services\UserService;

/**
 * Lớp xử lý quản trị, phân quyền người sử dụng
 *
 * @author Toanph <skype: toanph155>
 */
class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $u)
    {
        $this->userService = $u;
    }

    /**
     * khởi tạo dữ liệu mẫu, Load các file js, css của đối tượng
     *
     * @return View
     */
    public function index()
    {
        $data = $this->userService->index();
        return view("Users::User.index", $data);
    }

    /**
     * Load màn hình danh sách
     *
     * @param Request $request
     *
     * @return View
     */
    public function loadlist(Request $request)
    {
        $units = $this->userService->loadList($request->input());
        return response()->json($units);
    }

    /**
     * Lấy phòng ban
     * 
     * @param Request $request
     * @return string
     */
    // public function getDepartment(Request $request)
    // {
    //     return $this->userService->getHtmlOptDepartment($request->input());
    // }

    /**
     * View thêm mới người dùng
     * 
     * @param Request $request
     * @return View
     */
    public function create(Request $request)
    {
        $data = $this->userService->_create($request->input());
        return view('Users::User.add', $data);
    }

    /**
     * View sửa người dùng
     * 
     * @param Request $request
     * @return View
     */
    public function edit(Request $request)
    {
        $data = $this->userService->edit($request->input());
        return view('Users::User.add', $data);
    }

    /**
     * Cập nhật người dùng
     * 
     * @param Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $result = $this->userService->_update($request->input());
        return response()->json($result);
    }

    /**
     * Xóa người dùng
     * 
     * @param Request $request
     * @return Response
     */
    public function delete(Request $request)
    {
        $result = $this->userService->_delete($request->input());
        return response()->json($result);
    }

    /**
     * Tìm kiếm
     * 
     * @param Request $request
     * @return Response
     */
    public function search(Request $request)
    {
        $result = $this->userService->search($request->input());
        return response()->json($result);
    }
}
