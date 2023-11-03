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
use Modules\Core\Helpers\ForgetPassWordMailHelper;
use Modules\Frontend\Services\ExamService;

/**
 * Controller đăng nhập, đăng ký ở Cổng
 * 
 * @author khuongtq
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
     * Đăng nhập
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signIn(Request $request): JsonResponse
    {
        $result = $this->authService->signIn($request->input());
        return response()->json($result);
    }

    /**
     * View đăng ký
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function getSignUp(): View
    {
        return view('Frontend::auth.sign-up');
    }

    /**
     * Đăng ký
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signUp(Request $request): JsonResponse
    {
        try {
            $result = $this->authService->signUp($request->input());
            return response()->json($result);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Đăng ký thất bại!']);
        }
    }

    /**
     * View quên mật khẩu
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function getForgotPassword(): View
    {
        $objLibrary          = new Library();
        $arrResult           = array();
        $arrResult           = $objLibrary->_getAllFileJavaScriptCssArray('js', 'frontend/login/forgot-password.js', ',', $arrResult);
        $data['stringJsCss'] = json_encode($arrResult);

        return view('Frontend::auth.forgot-password', $data);
    }
    
    /**
     * Lấy lại mật khẩu
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $result = $this->authService->forgotPassword($request->input('email'));
        return response()->json($result);
    }

    /**
     * Đăng xuất
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logOut(Request $request): RedirectResponse
    {
        Auth::guard('frontend')->logout();
        if (!empty($_SESSION["hoithicchc"]['id'])) {
            session_destroy();
        }
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
