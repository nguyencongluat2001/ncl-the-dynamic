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
use Modules\Frontend\Services\Dashboard\BlogImagesService;

/**
 * Home controller
 */
class HomeController extends Controller
{  
    private $homeService;
    private $BlogService;
    private $BlogDetailService;

    public function __construct(
        BlogImagesService $BlogImagesService,
        HomeService $s,
        BlogService $BlogService,
        BlogDetailService $BlogDetailService
    ) {;
        $this->BlogImagesService = $BlogImagesService;
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
        $data = [];
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
    public function getRank(string $contestId): JsonResponse
    {
        $data = $this->homeService->getRank($contestId);
        return response()->json($data);
    }
}
