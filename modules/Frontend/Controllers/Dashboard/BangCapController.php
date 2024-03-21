<?php

namespace Modules\Frontend\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Frontend\Services\Dashboard\CateService;
use DB;
use Illuminate\Http\JsonResponse;
use Modules\Core\Ncl\Library;
use Modules\Frontend\Services\Dashboard\CategoryService;
use Modules\Frontend\Services\Dashboard\BangCapService;

/**
 * Quản trị kho
 *
 * @author Luatnc
 */
class BangCapController extends Controller
{

    private $bangCapService;
    private $categoryService;
    public function __construct(
        BangCapService $BangCapService,
        CategoryService $categoryService
    ) {
        $this->bangCapService = $BangCapService;
        $this->categoryService = $categoryService;
    }

    /**
     * khởi tạo dữ, Load các file js, css của đối tượng
     *
     * @return view
     */
    public function index(Request $request)
    {
        $data['getCategory'] = $this->categoryService
            ->whereIn('code_category', ['TT_02_N1', 'TT_03_N1'])
            ->get();
        // dd($data['getCategory']);
        return view('Frontend::Dashboard.Product.bangCap.index', $data);
    }
    /**
     * Load màn hình chỉnh sửa thông tin danh mục
     *
     * @param Request $request
     *
     * @return view
     */
    public function viewbangCap(Request $request)
    {
        $input = $request->all();
        $bangCap = $this->bangCapService->where('id', $input['id'])->first();
        if (empty($bangCap)) {
            return array('success' => false, 'message' => 'Không tồn tại đối tượng!');
        }
        $data['datas'] = $this->bangCapService->where('id', $input['id'])->first();
        return view('Frontend::Dashboard.product.bangCap.view', $data);
    }
    /**
     * Xóa danh mục
     *
     * @param Request $request
     *
     * @return Array
     */
    public function delete(Request $request)
    {
        $input = $request->all();
        $listids = trim($input['listitem'], ",");
        $ids = explode(",", $listids);
        foreach ($ids as $id) {
            if ($id) {
                $this->bangCapService->where('id', $id)->delete();
            }
        }
        return array('success' => true, 'message' => 'Xóa thành công');
    }
    /**
     * load màn hình danh sách
     *
     * @param Request $request
     *
     * @return json $return
     */
    public function loadList(Request $request)
    {
        $arrInput      = $request->input();
        $objResult     = $this->bangCapService->filter($arrInput);
        $data['datas'] = $objResult;
        return view("Frontend::Dashboard.product.bangCap.loadListBangCap", $data)->render();
    }
    /**
     * Cập nhật trạng thái
     */
    public function changeStatus(Request $request)
    {
        $arrInput = $request->all();
        $bangCap = $this->bangCapService->where('id', $arrInput['id']);
        if (!empty($bangCap->first())) {
            $this->bangCapService->where('id', $bangCap->first()->id)->update(['trang_thai' => $arrInput['status']]);
            $bangCap->update(['trang_thai' => $arrInput['status']]);
            return array('success' => true, 'message' => 'Cập nhật thành công!');
        } else {
            return array('success' => false, 'message' => 'Không tìm thấy dữ liệu!');
        }
    }
}
