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
class HealthCertificate extends Controller
{
    private $homeService;

    public function __construct(
        HomeService $s,
        BlogService $BlogService,
        BlogDetailService $BlogDetailService
    ) {;
        $this->BlogDetailService = $BlogDetailService;
        $this->BlogService = $BlogService;
        $this->homeService = $s;
    }

    /**
     * Load màn hình LÀM GIẤY KHÁM SỨC KHỎE
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): View
    {
        return view('Frontend::giayKham.index');
    }
}
