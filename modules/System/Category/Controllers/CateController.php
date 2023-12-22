<?php

namespace Modules\System\Category\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\System\Category\Services\CateService;
use DB;
use Modules\System\Category\Services\CategoryService;

/**
 * Quản trị danh mục
 *
 * @author Luatnc
 */
class CateController extends Controller
{

    public function __construct(
        CateService $CateService,
        CategoryService $categoryService
    ){
        $this->CateService = $CateService;
        $this->categoryService = $categoryService;
    }

    /**
     * khởi tạo dữ, Load các file js, css của đối tượng
     *
     * @return view
     */
    public function index(Request $request)
    {
        return view('Category::cate.index');
    }
     /**
     * Load màn hình them thông tin người dùng
     *
     * @param Request $request
     *
     * @return view
     */
    public function createForm(Request $request)
    {
        $input = $request->all();
        return view('Category::cate.edit');
    }
    /**
     * them danh mục
     *
     * @param Request $request
     *
     * @return view
     */
    public function create (Request $request)
    {
        $input = $request->input();
        $create = $this->CateService->store($input); 
        return $create;
    }
    /**
     * Load màn hình chỉnh sửa thông tin danh mục
     *
     * @param Request $request
     *
     * @return view
     */
    public function edit(Request $request)
    {
        $input = $request->all();
        $cates = $this->CateService->where('id', $input['id'])->first();
        if(empty($cates)){
            return array('success' => false, 'message' => 'Không tồn tại đối tượng!');
        }
        $data['datas'] = $this->CateService->where('id', $input['id'])->first();
        return view('Category::cate.edit', $data);
    }

    /**
     * Chỉnh sửa thông tin người dùng
     *
     * @param Request $request
     *
     * @return Array $respone
     */
    public function update(Request $request)
    {
        $input = $request->input();
        $respone = $this->CateService->updateOrCreateCategory($input);
        return $respone;
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
                $this->CateService->where('id',$id)->delete();
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
        $arrInput = $request->input();
        $data = array();
        $arrInput['sort'] = 'order';
        $arrInput['sortType'] = 1;
        $objResult = $this->CateService->filter($arrInput);
        $data['datas'] = $objResult;
        return view("Category::cate.loadlist", $data)->render();
    }
    /**
     * Cập nhật thông tin màn hình index
     */
    public function updateCategory(Request $request)
    {
        $arrInput = $request->all();
        $data = $this->CateService->_updateCategory($arrInput, $arrInput['id']);
        return $data;
    }
    /**
     * Cập nhật trạng thái
     */
    public function changeStatusCate(Request $request)
    {
        $arrInput = $request->all();
        $cate = $this->CateService->where('id', $arrInput['id']);
        if(!empty($cate->first())){
            $this->categoryService->where('cate', $cate->first()->code)->update(['status' => $arrInput['status']]);
            $cate->update(['status' => $arrInput['status']]);
            return array('success' => true, 'message' => 'Cập nhật thành công!');
        }else{
            return array('success' => false, 'message' => 'Không tìm thấy dữ liệu!');
        }
    }
}
