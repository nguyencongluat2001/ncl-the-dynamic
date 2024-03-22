<?php

namespace Modules\Frontend\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Core\Ncl\Library;
use Modules\Frontend\Services\Dashboard\BangCapService;
use Modules\Frontend\Services\HealthCertificateService;
use Modules\Frontend\Services\HomeService;
use Modules\Frontend\Services\Dashboard\BlogService;
use Modules\Frontend\Services\Dashboard\BlogDetailService;

/**
 * Home controller
 */
class DegressController extends Controller
{
    private $healthService;
    private $BlogDetailService;
    private $BlogService;
    private $bangCapService;

    public function __construct(
        BangCapService $bangCapService,
        HealthCertificateService $s,
        BlogService $BlogService,
        BlogDetailService $BlogDetailService
    ) {
        $this->bangCapService = $bangCapService;
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
        $data['getBlog'] = $this->BlogService->where('status', 1)
            ->whereIn('code_category', ['TT_02_N1', 'TT_03_N1'])
            ->get();
        if ($data['getBlog']->isNotEmpty()) {
            $blogCodes = $data['getBlog']->pluck('code_blog');
            $data['blogs_health'] = $this->BlogDetailService
                ->whereIn('code_blog', $blogCodes)
                ->get();
        } else {
            $data['blogs_health'] = collect();
        }
        // dd($data['blogs_health']);
        return view('Frontend::bangCap.index', $data);
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
