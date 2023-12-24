<?php
namespace Modules\Frontend\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Frontend\Services\Dashboard\CategoryService;
use Modules\Frontend\Services\Dashboard\CateService;
use DB;

/**
 * Quản trị danh mục
 *
 * @author Luatnc
 */
class CategoryController extends Controller
{

    public function __construct(
        CateService $CateService,
        CategoryService $CategoryService
    ){
        $this->CateService = $CateService;
        $this->CategoryService = $CategoryService;
    }
    
    /**
     * khởi tạo dữ, Load các file js, css của đối tượng
     *
     * @return view
     */
    public function indexCategory(Request $request)
    {
        $getCate = $this->CateService->where('status','1')->get()->toArray();
        $data['cate'] = $getCate;
        return view('Frontend::Dashboard.Category.category.indexCategory',compact('data'));
    }
    /**
     * load màn hình danh sách
     *
     * @param Request $request
     *
     * @return json $return
     */
    public function loadListCategory(Request $request)
    {
        $arrInput = $request->input();
        $data = array();
        $arrInput['sort'] = 'order';
        $arrInput['sortType'] = 1;
        $objResult = $this->CategoryService->filter($arrInput);
        $data['datas'] = $objResult;
        $data['param'] = $arrInput;
        // $data['pagination'] = $data['datas']->links('pagination.default');
        return view("Frontend::Dashboard.Category.category.loadlistCategory", $data)->render();
    }
     /**
     * hiển thị modal update + create thể loại
     *
     * @param Request $request
     *
     * @return view
     */
    public function createFormCategory(Request $request)
    {
        $input = $request->all();
        $getCate = $this->CateService->where('status','1')->get();
        $data['cates'] = $getCate;
        $data['dataCate'] = $input['cate'];
        $data['order'] = $this->CategoryService->select('id')->where('cate', $input['cate'])->count() + 1;
        return view('Frontend::Dashboard.Category.category.edit',$data);
    }
    /**
     * thêm + update thông tin thể loại
     *
     * @param Request $request
     *
     * @return view
     */
    public function createCategory (Request $request)
    {
        $input = $request->input();
        $create = $this->CategoryService->store($input); 
        return $create;
    }
    /**
     * Load màn hình chỉnh sửa thông tin thể loại
     *
     * @param Request $request
     *
     * @return view
     */
    public function edit(Request $request)
    {
        $input = $request->all();
        $cates = $this->CategoryService->where('id', $input['id'])->first();
        if(empty($cates)){
            return array('success' => false, 'message' => 'Không tồn tại đối tượng!');
        }
        $category = $this->CategoryService->where('id', $input['id'])->first();
        $data['cates'] = $this->CateService->select('*')->where('status','1')->get();
        $data['datas'] = $category;
        $data['order'] = $this->CategoryService->select('id')->count();
        $data['_id'] = $input['id'];
        // dd($data);
        return view('Frontend::Dashboard.Category.category.edit',$data);
    }


     /**
     * Xóa thể loại
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
                $this->CategoryService->where('id',$id)->delete();
            }
        }
        return array('success' => true, 'message' => 'Xóa thành công');
    }
    /**
     * Cập nhật thông tin màn hình index
     */
    public function updateCategoryCate(Request $request)
    {
        $arrInput = $request->all();
        $data = $this->CategoryService->_updateCategoryCate($arrInput, $arrInput['id']);
        return $data;
    }
    /**
     * Cập nhật trạng thái
     */
    public function changeStatusCategoryCate(Request $request)
    {
        $arrInput = $request->all();
        $list = $this->CategoryService->where('id', $arrInput['id']);
        if(!empty($list->first())){
            $list->update(['status' => $arrInput['status']]);
            return array('success' => true, 'message' => 'Cập nhật thành công!');
        }else{
            return array('success' => false, 'message' => 'Không tìm thấy dữ liệu!');
        }
    }
}
