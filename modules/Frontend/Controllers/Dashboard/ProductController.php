<?php

namespace Modules\Frontend\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Frontend\Services\Dashboard\CateService;
use DB;
use Modules\Frontend\Services\Dashboard\CategoryService;
use Modules\Frontend\Services\Dashboard\ProductService;

/**
 * Quản trị kho
 *
 * @author Luatnc
 */
class ProductController extends Controller
{

    private $productService;
    public function __construct(
        ProductService $ProductService
    ) {
        $this->productService = $ProductService;
    }

    /**
     * khởi tạo dữ, Load các file js, css của đối tượng
     *
     * @return view
     */
    public function index(Request $request)
    {
        return view('Frontend::Dashboard.product.giaykham.index');
    }
    /**
     * Load màn hình chỉnh sửa thông tin danh mục
     *
     * @param Request $request
     *
     * @return view
     */
    public function viewGiayKham(Request $request)
    {
        $input = $request->all();
        $giayKham = $this->productService->where('id', $input['id'])->first();
        if (empty($giayKham)) {
            return array('success' => false, 'message' => 'Không tồn tại đối tượng!');
        }
        $data['datas'] = $this->productService->where('id', $input['id'])->first();
        return view('Frontend::Dashboard.product.giaykham.view', $data);
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
                $this->productService->where('id', $id)->delete();
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
        $objResult     = $this->productService->filter($arrInput);
        $data['datas'] = $objResult;
        return view("Frontend::Dashboard.product.giaykham.loadListGiayKham", $data)->render();
    }
    /**
     * Cập nhật trạng thái
     */
    public function changeStatus(Request $request)
    {
        $arrInput = $request->all();
        $giayKham = $this->productService->where('id', $arrInput['id']);
        if (!empty($giayKham->first())) {
            $this->productService->where('id', $giayKham->first()->id)->update(['trang_thai' => $arrInput['status']]);
            $giayKham->update(['trang_thai' => $arrInput['status']]);
            return array('success' => true, 'message' => 'Cập nhật thành công!');
        } else {
            return array('success' => false, 'message' => 'Không tìm thấy dữ liệu!');
        }
    }
}
