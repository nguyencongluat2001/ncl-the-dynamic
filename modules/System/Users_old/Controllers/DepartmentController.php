<?php

namespace Modules\System\Users\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\System\Users\Services\DepartmentService;

/**
 * Lớp xử lý quản trị phòng ban
 *
 * @author Toanph <skype: toanph155>
 */
class DepartmentController extends Controller
{
    private $departmentService;

    public function __construct(DepartmentService $d)
    {
        $this->departmentService = $d;
    }

    /**
     * Thêm phòng ban
     *
     * @param Request $request
     * @return View
     */
    public function add(Request $request)
    {
        $data = $this->departmentService->create($request->input());
        if ($data['type'] == 'department') {
            return view('Users::Department.add', $data);
        } else {
            return view('Users::Unit.add', $data);
        }
    }

    /**
     * Sửa phòng ban
     *
     * @param Request $request
     * @return View|Response
     */
    public function edit(Request $request)
    {
        $role = $_SESSION["role"];
        $data = $this->departmentService->edit($request->input());
        if ($role === 'ADMIN_OWNER' && $data['check_dvtk'] === 'checked') {
            return response()->json(array('success' => false, 'message' => 'Bạn không thể sửa đơn vị này'));
        }
        if ($data['type'] == 'department') {
            return view('Users::Department.add', $data);
        } else {
            return view('Users::Unit.add', $data);
        }
    }

    /**
     * Cập nhật hoặc thêm mới
     * 
     * @param Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $result = $this->departmentService->update($request->input());
        return response()->json($result);
    }

    /**
     * Xóa đơn vị
     * 
     * @param Request $request
     * @return Response
     */
    public function delete(Request $request)
    {
        $result = $this->departmentService->delete($request->id);
        return response()->json($result);
    }
}
