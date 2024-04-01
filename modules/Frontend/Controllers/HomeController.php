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
        $data['stringJsCss'] = json_encode($arrResult);
        $data['getBlog'] = $this->BlogService->where('code_category', 'TT_01')->first();
        $data['blogs_details'] = $this->BlogDetailService->where('code_blog', $data['getBlog']->code_blog)->first();
        $data['getBlog_bang'] = $this->BlogService->where('code_category', 'TT_02')->first();
        $data['blogs_details_bang'] = $this->BlogDetailService->where('code_blog', $data['getBlog_bang']->code_blog)->first();

        $getBlog     = $this->BlogService->where('status', 1)->where('code_category', 'LIKE', '%TT_01%')->where('code_category', '!=', 'TT_01')->get();
        $data['getBlog_list'] = $getBlog->map(function ($blog) {
            $blogDetails = $this->BlogDetailService->where('code_blog', $blog->code_blog)->first();
            $blogImage = $this->BlogImagesService->where('code_blog', $blog->code_blog)->first();
            $blog->details = $blogDetails;
            $blog->blogImage = $blogImage;
            return $blog;
        });


        $data['getBlog_bang'] = $this->BlogService->where('status', 1)
            ->where('code_category', 'LIKE', '%TT_02%')
            ->where('code_category', '!=', 'TT_02')
            ->get();
        if ($data['getBlog_bang']->isNotEmpty()) {
            $blogCodes = $data['getBlog_bang']->pluck('code_blog');
            $data['blogs_health'] = $this->BlogDetailService
                ->whereIn('code_blog', $blogCodes)
                ->get();
            foreach ($data['blogs_health'] as $blog) {
                $blog->blogImage = $this->BlogImagesService->where('code_blog', $blog->code_blog)->first();
            }
        } else {
            $data['blogs_health'] = collect();
        }
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
