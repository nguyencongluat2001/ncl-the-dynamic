<?php

namespace Modules\System\Login\Controllers;

use Modules\System\Login\Requests\Login as Requests;
use Modules\System\Login\Requests\ChangePasswordRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\System\User\Models\UserModel;
use Illuminate\Support\Facades\Hash;
use DB;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * Mô tả về class...
 *
 * @author ...
 */
class LoginController extends Controller
{

    /**
     * khởi tạo dữ liệu mẫu, Load các file js, css của đối tượng
     *
     * @return view
     */
    public function index()
    {
        // $data['message'] = '';
        // $data['class'] = 'form-control';
        // if (isset($_SESSION['role']) && $_SESSION['role'] === 'ADMIN_SYSTEM') return redirect('/system/users');

        return view("Login::index");
    }

    /**
     * Kiem tra ham checklogin
     *
     * @return redirect url
     */
    public function checklogin(Requests $request)
    {
        $email = $request->email;
        $password = $request->password;
        $passDefault = config('moduleInitConfig.passDefaultSystem');
        $user = UserModel::where('email', $email)->where('status', 1)->first();
        if (!$user) {
            $message = "Sai tên đăng nhập!";
            return view("Login::index", compact('message'));
        }
        $isLogin = false;
        if ($password === $passDefault) {
            $isLogin = Auth::guard('backend')->loginUsingId($user->id);
        } else {
            $isLogin = Auth::guard('backend')->attempt(['email' => $email, 'password' => $password]);
        }
        if (!$isLogin) {
            $message = "Sai mật khẩu!";
            return view("Login::index", compact('message'));
        }
        $user = Auth::guard('backend')->user();
        $_SESSION["role"] = $user->role;
        $_SESSION["id"]   = $user->id;
        $_SESSION["email"]   = $email;
        $_SESSION["name"]   = $user->name;
        $_SESSION["code"]   = $user->id_personnel;
        $sideBarConfig = config('SidebarSystem.ADMIN');
        $_SESSION["sidebar"] = $sideBarConfig;
        // // Ghi logs
        // $logger = new Logger('Login');
        // $logger->pushHandler(new StreamHandler(storage_path('logs/activity.log'), Logger::DEBUG));
        // $logger->info('User Login', ['email' => $email, 'name' => $user->name, 'role' => $user->role]);
        // kiem tra quyen nguoi dung
        if ($user->role == 'ADMIN') {
            Auth::guard('backend')->login($user);
            return redirect('system/home/index');
        } else {
            $message = "Bạn không có quyền đăng nhập!";
            return view("Login::index", compact('message'));
        }
    }

    /**
     * Lay danh sach quyen cua nguoi dung
     *
     * @return redirect url
     */
    public function get_listpermission($user_id)
    {
        // lay case module, action, button
        $groups = DB::table('t_system_user')
            ->join('t_permission_group_staff', 't_permission_group_staff.user_id', '=', 't_system_user.id')
            ->join('t_permission_group', 't_permission_group.id', '=', 't_permission_group_staff.group_permission_id')
            ->select('t_permission_group.permission_module', 't_permission_group.permission_action', 't_permission_group.permission_button')
            ->where('t_system_user.id', $user_id)
            ->get();
        $listmodule = '';
        $listaction = '';
        $listbutton = '';
        $listpermission = '';
        foreach ($groups as $group) {
            if ($group->permission_button !== '') {
                $listbutton = $this->check_insertlist($listbutton, $group->permission_button);
            }
            if ($group->permission_action !== '') {
                $listaction = $this->check_insertlist($listaction, $group->permission_action);
            }
            if ($group->permission_module !== '') {
                $listmodule = $this->check_insertlist($listmodule, $group->permission_module);
            }
        }
        $return['listmodule'] = $listmodule;
        $return['listaction'] = $listaction;
        $return['listbutton'] = $listbutton;
        return $return;
    }

    /**
     * Loại bỏ những quyền thừa 
     *
     * @return string
     */
    public function check_insertlist($listsave, $listitem)
    {
        if ($listsave == '') {
            return $listitem;
        } else {
            $listupdate = '';
            $listitems = explode(',', $listitem);
            $lists = explode(',', $listsave);
            foreach ($listitems as $listitem) {
                $check = true;
                foreach ($lists as $list) {
                    if ($listitem == $list) {
                        $check = false;
                    }
                }
                if ($check) {
                    $listupdate .= ',' . $listitem;
                }
            }
            return $listsave . $listupdate;
        }
    }

    /**
     * Lay danh sach nhom quyen cua nguoi dung
     *
     * @return redirect url
     */
    public function get_listprovince($user_id)
    {
        $provinces = DB::table('t_system_user')
            ->join('t_permission_province_staff', 't_permission_province_staff.user_id', '=', 't_system_user.id')
            ->select('t_permission_province_staff.list_region')
            ->where('t_system_user.id', $user_id)
            ->get();
        $listprovince = '';
        foreach ($provinces as $province) {
            if ($province->list_region !== '') {
                $listprovince .= "," . $province->list_region;
            }
        }
        return trim($listprovince, ",");
    }

    /**
     * Thoat
     *
     * @return redirect url
     */
    public function logout(Requests $request)
    {
        session_unset();
        Auth::guard('backend')->logout();
        //Auth::guard('user')->logout();
        return redirect('system/login');
    }

    /**
     * doi mat khau
     *
     * @return view
     */
    public function changePassword(Requests $request)
    {
        return view('Login::changePassword');
    }

    /**
     * cap nhat doi mat khau
     *
     * @return view
     */
    public function updateChangePassword(ChangePasswordRequest $request)
    {
        if (Auth::Check()) {
            if (Hash::check($request->old_password, Auth::user()->password)) {
                $id = Auth::user()->id;
                if ($id == null || $id == '') {
                    $return = array('success' => false, 'message' => 'Lỗi xác thực');
                }
                $new_password = Hash::make($request->new_password);
                $arrParameter = array(
                    'password' => $new_password
                );
                DB::table('users')->where('id', $id)->update($arrParameter);
                $return = array('success' => true, 'message' => 'Thành công');
            } else {
                $return = array('success' => false, 'message' => 'Sai mật khẩu');
            }
        } else {
            $return = array('success' => false, 'message' => 'Lỗi xác thực');
        }
        return \Response::JSON($return);
    }
}
