<?php

namespace Modules\Frontend\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Core\Ncl\Library;
use Modules\Frontend\Services\HomeService;

/**
 * Home controller
 */
class HomeController extends Controller
{
    private $homeService;

    public function __construct(HomeService $s)
    {
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
