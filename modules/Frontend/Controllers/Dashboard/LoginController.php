<?php

namespace Modules\Frontend\Controllers\Dashboard;

use Modules\Frontend\Requests\Login as Requests;
use Modules\Frontend\Requests\ChangePasswordRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Frontend\Models\Dashboard\UserModel;
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
        return view("Frontend::Dashboard.Login.index");
    }
        /**
     * login  file kết quả
     *
     * @param Request $request
     *
     * @return json $return
     */
    public function checklogin(Request $request)
    { 
        $arrInput = $request->input();
            $param = [
                'username'=> $arrInput['username'],
                // 'pwd'=> $arrInput['pwd']
            ];
            $response = Http::withBody(json_encode($param),'application/json')->post('118.70.182.89:89/api/PACS/login');
            $response = $response->getBody()->getContents();
            $response = json_decode($response,true);
            $_SESSION["username"] = $user->role;
            return view("Frontend.home.index", $data)->render();
    } 
}
