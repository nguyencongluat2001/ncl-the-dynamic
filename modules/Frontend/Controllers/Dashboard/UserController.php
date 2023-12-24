<?php

namespace Modules\Frontend\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Modules\Frontend\Services\Dashboard\UserInfoService;
use Modules\Frontend\Services\Dashboard\UserService;
use Modules\Frontend\Models\Dashboard\AuthenticationOTPModel;
use Modules\System\Helpers\PaginationHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Modules\Base\Helpers\ForgetPassWordMailHelper;
use Modules\Frontend\Models\Dashboard\UserPassOldModel;

/**
 *
 * @author Luatnc
 */
class UserController extends Controller
{
    public function __construct(
        userInfoService $userInfoService,
        UserService $userService
    ){
        $this->userInfoService = $userInfoService;
        $this->userService = $userService;
    }

    /**
     * màn hình danh sách người dùng
     *
     * @return view
     */
    public function index(Request $request)
    {
        return view('Frontend::Dashboard.User.index');
    }
    /**
     * user_info
     *
     * @return view
     */
    public function indexUserInfo(Request $request)
    {
        $data = $this->userService->where('id',$_SESSION["id"])->first();
        $userInfo = $this->userInfoService->where('user_id',$_SESSION['id'])->first();
        $data['company'] = !empty($userInfo->company)?$userInfo->company:null;
        $data['position'] = !empty($userInfo->position)?$userInfo->position:null;
        $data['date_join'] = !empty($userInfo->date_join)?$userInfo->date_join:null;
        return view('Frontend::Dashboard..User.userInfor.index',compact('data'));
    }
     /**
     * thay đổi màu sắc trang web
     *
     * @return view
     */
    public function editColorView(Request $request)
    {
        $input = $request->all();
        if(!empty($input['is_checkbox'])){
            $checkInfo = $this->userInfoService->where('user_id',$input['id'])->first();
            if($checkInfo){
                $update = $this->userInfoService->where('user_id',$input['id'])->update(['color_view'=> '1']);
            }else{
                $create = $this->userInfoService->create(['id'=>(string) \Str::uuid(),'color_view'=> '1','user_id'=> $input['id']]);
            }
            $_SESSION["color_view"] = 1;
        }else{
            $checkInfo = $this->userInfoService->where('user_id',$input['id'])->first();
            if($checkInfo){
                $update = $this->userInfoService->where('user_id',$input['id'])->update(['color_view'=> '2']);
            }else{
                $create = $this->userInfoService->create(['id'=>(string) \Str::uuid(),'color_view'=> '2','user_id'=> $input['id']]);
            }
            $_SESSION["color_view"] = 2;
        }
        return array('success' => true, 'message' => 'Cập nhật thành công');
    }

    /**
     * Load màn hình thêm mới người dùng
     *
     * @param Request $request
     *
     * @return view
     */
    public function add(Request $request)
    {
        $input = $request->all();
        $data = $this->userService->addUserDisplay($input);
        return view('Frontend::Dashboard.User.add', $data);
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
        return view('Frontend::Dashboard.User.edit');
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
        $create = $this->userService->store($input,$_FILES); 
        return $create;
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
        $data = $this->userService->editUser($input);
        return view('Frontend::Dashboard.User.edit',compact('data'));
    }

     /**
     * Xóa người dùng
     *
     * @param Request $request
     *
     * @return Array
     */
    public function delete(Request $request)
    {
        $input = $request->all();
        if($_SESSION['role'] != 'ADMIN' && $_SESSION['role'] != 'MANAGE' && $_SESSION['role'] != 'CV_ADMIN'){
            return array('success' => false, 'message' => 'Rất tiếc! bạn ko có quyền. Vui lòng liên hệ hỗ trợ FinTop.');
        }
        $listids = trim($input['listitem'], ",");
        $ids = explode(",", $listids);
        foreach ($ids as $id) {
            if ($id) {
                $this->userService->where('id',$id)->delete();
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
        $paginationHelper = new PaginationHelper();
        if($_SESSION['role'] != 'ADMIN' && $_SESSION['role'] != 'MANAGE'){
            if($_SESSION['role'] == 'CV_ADMIN' ){
                if($_SESSION['role'] == '' || $request['role'] == null){
                    $request['role'] = ['CV_ADMIN','CV_PRO','CV_BASIC','SALE_ADMIN','SALE_BASIC','USERS'];
                }else{
                    $request['role'] = array($request['role']);
                }
            }elseif($_SESSION['role'] == 'SALE_ADMIN'){
                if($_SESSION['role'] == '' || $request['role'] == null){
                    $request['role'] = ['SALE_ADMIN','SALE_BASIC'];
                }else{
                    $request['role'] = array($request['role']);
                }
            }
        }else{
            if($request['role'] == '' || $request['role'] == null){
                unset($request['role']);
            }else{
                $request['role'] = array($request['role']);
            }
        }
        $arrInput = $request->input();
        $data = array();
        $param = $arrInput;
        $objResult = $this->userService->filter($param);
        $data['datas'] = $objResult;
        return view("Frontend::Dashboard.User.loadlist", $data)->render();
    }
     /**
     * hiển thị modal đổi mật khẩu
     *
     * @param Request $request
     *
     * @return view
     */
    public function changePass(Request $request)
    {
        $input = $request->all();
        $data['id'] = $input['id'];
        $users = $this->userService->where('id',$input['id'])->first();
        $data['email_acc'] = $users['email'];
        return view('Frontend::Dashboard.User.userInfor.edit',compact('data'));
    }
    /**
     * Cập nhật mật khẩu
     *
     * @param Request $request
     *
     * @return view
     */
    public function updatePass(Request $request)
    {
        $input = $request->all();
        // dd($input);
        if (Auth::guard('web')->attempt(['email' => $input['email_acc'],'password' => $input['password_old']])) {
            if($input['password_new'] != $input['password_retype_change']){
                return array('success' => false, 'message' => 'Nhập lại mật khẩu không khớp!');
            }
            $user = $this->userService->where('email',$input['email_acc'])->first();
            if(!empty($user)){
                Carbon::setLocale('vi');
                $now = Carbon::now();
                $arrPassOld = UserPassOldModel::where('user_id', $user->id)->get();
                foreach($arrPassOld as $key => $value){
                    $created = Carbon::create($value->created_at);
                    if(password_verify($input['password_new'], $value->password)){
                        return array('success' => false, 'message' => 'Bạn đã sử dụng mật khẩu này <span style="color:red">' . $created->diffForHumans($now) . '</span>. Hãy nhập một mật khẩu khác!');
                    }
                }
                $user->update(['password' => Hash::make($input['password_new'])]);
                UserPassOldModel::insert(['id' => (string)\Str::uuid(), 'user_id' => $user->id, 'password' => Hash::make($input['password_new']), 'created_at' => date('Y-m-d H:i:s')]);
                return array('success' => true, 'message' => 'Đổi mật khẩu thành công');
            }else{
                return array('success' => false, 'message' => 'Không tồn tại tài khoản!');
            }
        } else {
            return array('success' => false, 'message' => 'Mật khẩu cũ chưa chính xác!');
        }
    }
     /**
     * Gửi mail đến người dùng
     * 
     * @param Array $input
     */
    public function sendMail($input)
    {
        $stringHtml = file_get_contents(base_path() . '\storage\templates\forgetPassword\tem_forget.html');
        // Lấy dữ liệu
        $user = $this->userService->where('id',$input['user_id'])->first();
        $data['date'] = 'Ngày ' . date('d') . ' tháng ' . date('m') . ' năm ' . date('Y');
        $data['email'] = !empty($input['email_acc'])?$input['email_acc']:null;
        $data['name'] = $user->name;
        $data['phone'] = $user->phone;
        $data['mailto'] = $input['email_acc'];
        $data['passwordNew'] = $input['password_new'];
        $data['subject'] = 'Công ty TNHH Đầu tư & Phát triển FinTop';
        // Gửi mail
        (new ForgetPassWordMailHelper($data['email'], $data['email'], $stringHtml, $data))->send($data);
    }
    
    /**
     * Cập nhật trạng thái
     */
    public function changeStatus(Request $request)
    {
        $input = $request->all();
        $users = $this->userService->where('id', $input['id'])->first();
        if(!empty($users)){
            $users->update(['status' => $input['status']]);
            return array('success' => true, 'message' => 'Cập nhật thành công!');
        }else{
            return array('success' => false, 'message' => 'Không tìm thấy dữ liệu!');
        }
    }
    /**
    * view form OTP
    */
    public function sent_OTP(Request $request)
    {
        $input = $request->all();
        $data = $this->userService->sent_OTP($input);
        return $data;
    }
      /**
     * hiển thị modal đổi mật khẩu
     *
     * @param Request $request
     *
     * @return view
     */
    public function registerIntroduce(Request $request ,$id)
    {
        $input = $request->all();
        $checkUser = $this->userService->where('id_personnel', $id)->first();
        if(!empty($checkUser)){
            $data['user_introduce'] = $checkUser['id_personnel'];
            $data['user_introduce_name'] = $checkUser['name'];
            $data['user_introduce_id'] = $checkUser['id_personnel'];
            return view('auth.register.index',compact('data'));
        }else{
            return view('Uome::404_registerUserCode');
        }
    }
     /**
    * lấy thông tin nhân viên giới thiệu
    */
    public function getUser(Request $request)
    {
        $input = $request->all();
        $selectUser = $this->userService->where('id_personnel',$input['code_introduce'])->first();
        if($input['code_introduce'] == ''){
            return '';
        }
        if(isset($selectUser)){
            return array('success' => true,'data' => $selectUser, 'message' => 'Nhân viên giới thiệu: '.$selectUser->name);
        }else{
            return array('success' => false, 'message' => 'Mã nhân viên không chính xác , vui lòng thử lại!!!!');
        }
    }
}
