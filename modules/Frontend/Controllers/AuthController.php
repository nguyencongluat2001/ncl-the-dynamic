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
        $objLibrary          = new Library();
        $arrResult           = array();
        $arrResult           = $objLibrary->_getAllFileJavaScriptCssArray('js', 'frontend/login/auth.js', ',', $arrResult);
        $arrResult           = $objLibrary->_getAllFileJavaScriptCssArray('js', 'assets/jquery.validate.js', ',', $arrResult);
        $data['stringJsCss'] = json_encode($arrResult);
        $nextUrl = '';
        if ($request->input('path') && $request->input('path') != '') {
            $nextUrl = '/' . $request->input('path');
            if ($request->input('param') && $request->input('param') != '') $nextUrl .= '/' . $request->input('param');
            if ($request->input('query') && $request->input('query') != '') $nextUrl .= '?' . $request->input('query');
        }
        $data['nextUrl'] = $nextUrl;
        return view('Frontend::auth.sign-in', $data);
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
                // 'pwd'=> $arrInput['pwd']
            ];
            $response = Http::withBody(json_encode($param),'application/json')->post('118.70.182.89:89/api/PACS/login');
            $response = $response->getBody()->getContents();
            $response = json_decode($response,true);
            if($response['status'] == true){
                $_SESSION["username"] = $response['loginModel']['username'];
                // return view('Frontend::home.index');
                // return view("Frontend::home.index");
                return $response;

            }
    } 
}
