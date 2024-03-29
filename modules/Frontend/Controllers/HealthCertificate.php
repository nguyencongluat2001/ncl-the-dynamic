<?php

namespace Modules\Frontend\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Core\Ncl\Library;
use Modules\Frontend\Services\HealthCertificateService;
use Modules\Frontend\Services\HomeService;
use Modules\Frontend\Services\Dashboard\BlogService;
use Modules\Frontend\Services\Dashboard\BlogDetailService;
use Modules\Frontend\Services\Dashboard\BlogImagesService;

/**
 * Home controller
 */
class HealthCertificate extends Controller
{
    private $healthService;
    private $BlogDetailService;
    private $BlogService;

    public function __construct(
        BlogImagesService $BlogImagesService,
        HealthCertificateService $s,
        BlogService $BlogService,
        BlogDetailService $BlogDetailService
    ) {
        $this->BlogImagesService = $BlogImagesService;
        $this->BlogDetailService = $BlogDetailService;
        $this->BlogService = $BlogService;
        $this->healthService = $s;
    }

    /**
     * Load màn hình LÀM GIẤY KHÁM SỨC KHỎE
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): View
    {
        $objLibrary           = new Library();
        $arrResult            = array();
        $arrResult            = $objLibrary->_getAllFileJavaScriptCssArray('js', 'frontend/home/home.js', ',', $arrResult);
        $arrResult            = $objLibrary->_getAllFileJavaScriptCssArray('js', 'assets/jquery.validate.js', ',', $arrResult);
        $data['stringJsCss']  = json_encode($arrResult);
        $getBlog     = $this->BlogService->where('status', 1)->where('code_category', 'LIKE', '%TT_01%')->where('code_category', '!=', 'TT_01')->get();
        $data['getBlog'] = $getBlog->map(function ($blog) {
            $blogDetails = $this->BlogDetailService->where('code_blog', $blog->code_blog)->first();
            $blogImage = $this->BlogImagesService->where('code_blog', $blog->code_blog)->first();
            $blog->details = $blogDetails;
            $blog->blogImage = $blogImage;
            return $blog;
        });
        $data_baivietgiaykham = $this->BlogService->where('code_category', 'TT_01')->first();
        $data['getBlogView'] = $this->BlogDetailService->where('code_blog', $data_baivietgiaykham->code_blog)->first();
        return view('Frontend::giayKham.index', $data);
    }

    /**
     * Thêm thông tin liên hệ người muốn làm giấy khám
     *
     * @param Request $request
     *
     * @return view
     */
    public function create(Request $request)
    {
        $input = $request->input();
        $create = $this->healthService->store($input, $_FILES);
        return $create;
    }
}
