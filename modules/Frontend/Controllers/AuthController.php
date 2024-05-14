<?php

namespace Modules\Frontend\Controllers;

use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Frontend\Services\AuthService;
use Modules\Frontend\Services\SubjectService;
use Modules\Core\Ncl\Library;
use Illuminate\Support\Facades\Auth;
use Modules\Frontend\Services\ExamService;
use Modules\Frontend\Models\UnitsModel;
use DB;
use Illuminate\Support\Facades\Http;
use Str;

/**
 * Controller đăng nhập, đăng ký ở Cổng
 * 
 * @author luatnc
 */
class AuthController
{
    private $authService;
    private $subjectService;
    private $examService;

    public function __construct(
        AuthService $a,
        SubjectService $s,
        ExamService $e
    ) {
        $this->authService = $a;
        $this->subjectService = $s;
        $this->examService = $e;
    }

    /**
     * View đăng nhập
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function getSignIn(Request $request): View
    {
        return view('Frontend::auth.sign-in');
    }
       /**
     * login  file kết quả
     *
     * @param Request $request
     *
     * @return json $return
     */
    public function signIn(Request $request)
    { 
        $arrInput = $request->input();
            $param = [
                'username'=> $arrInput['username'],
                // $arrInput['pwd']
                'pwd'=> $arrInput['password']
            ];
            $response = Http::withBody(json_encode($param),'application/json')->post('118.70.182.89:89/api/PACS/login');
            $response = $response->getBody()->getContents();
            $response = json_decode($response,true);
            if($response['status'] == true){
                $_SESSION["username"] = $response['loginModel']['username'];
                $_SESSION["pwd"] = $param['pwd'];
                $_SESSION["accessionnumber"] = -1;
                return $response;

            }
    } 
       /**
     * dang xuat
     *
     * @param Request $request
     *
     * @return json $return
     */
    public function logout(Request $request)
    { 
        if(!empty($_SESSION['username'])){
            session_destroy();
        }
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return view('Frontend::auth.sign-in');
    } 
    
}
