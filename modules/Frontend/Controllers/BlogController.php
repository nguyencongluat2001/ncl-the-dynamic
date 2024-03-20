<?php

namespace Modules\Frontend\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Core\Ncl\Library;
use Modules\Frontend\Services\BlogService;
use Modules\Frontend\Services\Dashboard\BangCapService;
use Modules\Frontend\Services\HealthCertificateService;
use Modules\Frontend\Services\HomeService;
// use Modules\Frontend\Services\Dashboard\BlogService;
use Modules\Frontend\Services\Dashboard\BlogDetailService;

/**
 * BlogController
 */
class BlogController extends Controller
{
    private $homeService;
    private $BlogDetailService;
    private $BlogService;
    private $healthService;

    private $bangCapService;

    public function __construct(
        HealthCertificateService $healthService,
        BangCapService $bangCapService,
        HomeService $s,
        BlogService $BlogService,
        BlogDetailService $BlogDetailService
    ) {
        $this->bangCapService    = $bangCapService;
        $this->homeService       = $s;
        $this->BlogDetailService = $BlogDetailService;
        $this->BlogService       = $BlogService;
        $this->healthService     = $healthService;
    }

    /**
     * Load màn hình trang chủ
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function index($code_blog): View
    {
        $objLibrary          = new Library();
        $arrResult           = array();
        $arrResult           = $objLibrary->_getAllFileJavaScriptCssArray('js', 'frontend/home/home.js', ',', $arrResult);
        $arrResult           = $objLibrary->_getAllFileJavaScriptCssArray('js', 'assets/jquery.validate.js', ',', $arrResult);
        $data['stringJsCss'] = json_encode($arrResult);
        $data['getBlog']      = $this->BlogService->where('code_blog', $code_blog)->first();
        $data['blogs_health'] = $this->BlogDetailService->where('code_blog', $data['getBlog']->code_blog)->first();
        // dd($data['blogs_health']);
        return view('Frontend::blog.index', $data);
    }


    /**
     * Thêm thông tin liên hệ người muốn làm giấy khám
     *
     * @param Request $request
     *
     * @return view
     */
    public function createGiayKham(Request $request)
    {
        $input = $request->input();
        $create = $this->healthService->store($input, $_FILES);
        return $create;
    }

    /**
     * Thêm thông tin liên hệ người muốn làm giấy khám
     *
     * @param Request $request
     *
     * @return view
     */
    public function createBangCap(Request $request)
    {
        $input = $request->input();
        $create = $this->bangCapService->store($input, $_FILES);
        return $create;
    }
}
