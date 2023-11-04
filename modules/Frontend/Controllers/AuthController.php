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
        $objLibrary          = new Library();
        $arrResult           = array();
        $arrResult           = $objLibrary->_getAllFileJavaScriptCssArray('js', 'frontend/login/auth.js', ',', $arrResult);
        $arrResult           = $objLibrary->_getAllFileJavaScriptCssArray('js', 'assets/jquery.validate.js', ',', $arrResult);
        $data['stringJsCss'] = json_encode($arrResult);
        $data['tinh'] =  UnitsModel::whereNull('code_huyen')->get();
        return view('Frontend::auth.sign-up',$data);
    }

    /**
     * Đăng ký
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signUp(Request $request): JsonResponse
    {
        // try {
            $result = $this->authService->signUp($request->input());
            return response()->json($result);
        // } catch (Exception $e) {
        //     return response()->json(['success' => false, 'message' => 'Đăng ký thất bại!']);
        // }
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
        if (!empty($_SESSION['id'])) {
            session_destroy();
        }
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
     /**
     * Lấy đơn vị quốc gia
     * 
     */
    public function getUnitApi(Request $request)
    {
        $response = Http::get('https://provinces.open-api.vn/api/?depth=3');
        $response = $response->getBody()->getContents();
        $response = json_decode($response,true);
        foreach($response as $value){
            // dd($value);
            // tỉnh
            $codeTinh = $value['code'];
            if($codeTinh < 155){
                $check = UnitsModel::where('code_tinh',$codeTinh)->first();
                if(!isset($check)){
                    if(isset($value['districts'])){
                        $dataTinh = [
                            'id'=> (string)Str::uuid(),
                            'code_tinh'=> $codeTinh,
                            'code_huyen'=> null,
                            'code_xa'=> null,
                            'name'=> $value['name'],
                            'name_type' => $value['division_type']
                        ];
                        $createTinh = UnitsModel::insert($dataTinh);
                    }
                    // huuyeen
                    if(isset($value['districts'])){
                        foreach($value['districts'] as $valueHuyen){
                            $dataHuyen = [
                                'id'=> (string)Str::uuid(),
                                'code_tinh'=> $codeTinh,
                                'code_huyen'=> $valueHuyen['code'],
                                'code_xa'=> null,
                                'name'=> $valueHuyen['name'],
                                'name_type' => $valueHuyen['division_type']
                            ];
                            $createHuyen = UnitsModel::insert($dataHuyen);
                            // xa
                                foreach($valueHuyen['wards'] as $valueXa){
                                    $dataXa = [
                                        'id'=> (string)Str::uuid(),
                                        'code_tinh'=> $codeTinh,
                                        'code_huyen'=> $valueHuyen['code'],
                                        'code_xa'=> $valueXa['code'],
                                        'name'=> $valueXa['name'],
                                        'name_type' => $valueXa['division_type']
                                    ];
                                    $createXa = UnitsModel::insert($dataXa);
                                }
                        }
                    }
                }
            }
           
        }
        dd('ok');
    }
       /// Danh sách huyện
     /**
     *
     * @param Request $request
     *
     * @return view
     */
    public function getHuyen(Request $request)
    {
        $input = $request->all();
        $datas['huyen'] =  UnitsModel::where('code_tinh',$input['codeTinh'])->whereNull('code_xa')->select('code_huyen','name')->get()->toArray();
        
        return response()->json([
            'data' => $datas,
            'status' => true
        ]);
    }
      /// Danh sách phường xã
     /**
     *
     * @param Request $request
     *
     * @return view
     */
    public function getXa(Request $request)
    {
        $input = $request->all();
        $datas['xa'] =  UnitsModel::where('code_huyen',$input['codeHuyen'])->select('code_xa','name')->get()->toArray();
        
        return response()->json([
            'data' => $datas,
            'status' => true
        ]);
    }
}
