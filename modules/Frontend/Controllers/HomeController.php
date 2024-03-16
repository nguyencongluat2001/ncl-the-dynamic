<?php

namespace Modules\Frontend\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Core\Ncl\Library;
use Modules\Frontend\Services\HomeService;
use Modules\Frontend\Services\Dashboard\BlogService;
use Modules\Frontend\Services\Dashboard\BlogDetailService;

/**
 * Home controller
 */
class HomeController extends Controller
{
    private $homeService;

    public function __construct(
        HomeService $s,
        BlogService $BlogService,
        BlogDetailService $BlogDetailService
        )
    {;
        $this->BlogDetailService = $BlogDetailService;
        $this->BlogService = $BlogService;
        $this->homeService = $s;
    }

    /**
     * Load màn hình trang chủ
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): View
    {
        $objLibrary          = new Library();
        $arrResult           = array();
        $arrResult           = $objLibrary->_getAllFileJavaScriptCssArray('js', 'frontend/home/home.js', ',', $arrResult);
        $arrResult           = $objLibrary->_getAllFileJavaScriptCssArray('js', 'assets/jquery.validate.js', ',', $arrResult);
        $data['stringJsCss'] = json_encode($arrResult);
        $data['getBlog'] = $this->BlogService->where('code_category','TT_01')->first();
        $data['blogs_details'] = $this->BlogDetailService->where('code_blog',$data['getBlog']->code_blog)->first();
        $data['getBlog_bang'] = $this->BlogService->where('code_category','TT_02')->first();
        $data['blogs_details_bang'] = $this->BlogDetailService->where('code_blog',$data['getBlog_bang']->code_blog)->first();
        return view('Frontend::home.index', $data);
    }

    /**
     * Lấy các dữ liệu màn hình trang chủ
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(): JsonResponse
    {
        $data = $this->homeService->getData();
        return response()->json($data);
    }

        /**
     * Lấy các dữ liệu màn hình trang chủ
     * 
     * @param string $contestId ID đợt thi
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRank(string $contestId) : JsonResponse {
        $data = $this->homeService->getRank($contestId);
        return response()->json($data);
    }
}
