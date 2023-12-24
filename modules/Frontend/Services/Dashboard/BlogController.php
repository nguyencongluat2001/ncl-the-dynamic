<?php

namespace Modules\Frontend\Services\Dashboard;

use App\Http\Controllers\Controller;
use Modules\Frontend\Services\Dashboard\CategoryService;
use Modules\Frontend\Services\Dashboard\UserService;
use Modules\Frontend\Services\Dashboard\CateService;
use Modules\Frontend\Services\Dashboard\BlogService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use DB;
use str;

/**
 * Phân quyền người dùng 
 *
 * @author Luatnc
 */
class BlogController extends Controller
{
    public function __construct(
        UserService $userService,
        CateService $cateService,
        CategoryService $categoryService,
        BlogService $blogService
    ){
        $this->userService = $userService;
        $this->cateService = $cateService;
        $this->categoryService = $categoryService;
        $this->blogService = $blogService;
    }

    /**
     * màn hình danh sách người dùng
     *
     * @return view
     */
    public function index(Request $request)
    {
        $cate = $this->cateService->where('code_cate','DM_BLOG')->first();
        if(!empty($cate)){
            $category = $this->categoryService->select('code_category','name_category')->where('cate',$cate->code_cate)->orderBy('created_at','ASC')->get()->toArray();
        }
        $data['category'] = isset($category) ? $category : [];
        return view('dashboard.blog.index',compact('data'));
    }
    /**
     * user_info
     *
     * @return view
     */
    // public function indexUserInfo(Request $request)
    // {
    //     $data = $this->userService->where('id',$_SESSION["id"])->first();
    //     $userInfo = $this->userInfoService->where('user_id',$_SESSION['id'])->first();
    //     $data['company'] = !empty($userInfo->company)?$userInfo->company:null;
    //     $data['position'] = !empty($userInfo->position)?$userInfo->position:null;
    //     $data['date_join'] = !empty($userInfo->date_join)?$userInfo->date_join:null;
    //     return view('dashboard.users.userInfor.index',compact('data'));
    // }
    /**
     * Load màn hình thêm mới người dùng
     *
     * @param Request $request
     *
     * @return view
     */
    // public function add(Request $request)
    // {
    //     $input = $request->all();
    //     $data = $this->userService->addUserDisplay($input);
    //     return view('Users::User.add', $data);
    // }
     /**
     * Load màn hình thêm bài viết
     *
     * @param Request $request
     *
     * @return view
     */
    public function createForm(Request $request)
    {
        $input = $request->all();
        $category = $this->categoryService->where('cate','DM_BLOG')->orderBy('order')->get()->toArray();
        $data['category'] = $category;
        $data['code'] = $input['category'];
        return view('dashboard.blog.edit',compact('data'));
    }
    /**
     * Thêm thông tin người dùng
     *
     * @param Request $request
     *
     * @return view
     */
    public function create (Request $request)
    {
        $input = $request->input();
        // dd($input,$_FILES);
        $create = $this->blogService->store($input,$_FILES); 
        return array('success' => true, 'message' => 'Cập nhật thành công');
    }
    /**
     * Load màn hình chỉnh sửa thông tin người dùng
     *
     * @param Request $request
     *
     * @return view
     */
    public function edit(Request $request)
    {
        $input = $request->all();
        $category = $this->categoryService->where('cate','DM_BLOG')->get()->toArray();
        $data = $this->blogService->editBlog($input);
        $data['category'] = $category;
        return view('dashboard.blog.edit',compact('data'));
    }

     /**
     * Xóa bài viết
     *
     * @param Request $request
     *
     * @return Array
     */
    public function delete(Request $request)
    {
        $input = $request->all();
        $this->blogService->delete($input);
        
        return array('success' => true, 'message' => 'Xóa thành công');
    }
     /**
     * Màn hình thông tin bài viết
     *
     * @param Request $request
     *
     * @return view
     */
    public function infor(Request $request)
    {
        $input = $request->all();
        $dataInfor = $this->blogService->infor($input);
        // dd($dataInfor);
        return view('dashboard.blog.infor',compact('dataInfor'));
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
        $param = $arrInput;
        if($param['category'] == '' || $param['category'] == null){
            unset($param['category']);
        }
        $objResult = $this->blogService->filter($param);
        $data['datas']= $objResult;
        return view("dashboard.blog.loadlist", $data)->render();
    }
    
    /**
     * Upload file nội dung bài viết
     */
    public function uploadFileCK(Request $request)
    {
        $data = $this->blogService->uploadFileCK($request->all());
        return $data;
    }
    /**
     * Cập nhật trạng thái
     */
    public function changeStatus(Request $request)
    {
        $arrInput = $request->all();
        $cate = $this->blogService->where('id', $arrInput['id']);
        if(!empty($cate->first())){
            $cate->update(['status' => $arrInput['status']]);
            return array('success' => true, 'message' => 'Cập nhật thành công!');
        }else{
            return array('success' => false, 'message' => 'Không tìm thấy dữ liệu!');
        }
    }
}
