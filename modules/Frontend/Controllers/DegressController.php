<?php

namespace Modules\Frontend\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Core\Ncl\Library;
use Modules\Frontend\Services\Dashboard\BangCapService;
use Modules\Frontend\Services\HealthCertificateService;
use Modules\Frontend\Services\HomeService;
use Modules\Frontend\Services\Dashboard\BlogService;
use Modules\Frontend\Services\Dashboard\BlogDetailService;
use Modules\Frontend\Services\Dashboard\BlogImagesService;

/**
 * Home controller
 */
class DegressController extends Controller
{
    private $healthService;
    private $BlogDetailService;
    private $BlogService;
    private $bangCapService;

    private $BlogImagesService;

    public function __construct(
        BlogImagesService $BlogImagesService,
        BangCapService $bangCapService,
        HealthCertificateService $s,
        BlogService $BlogService,
        BlogDetailService $BlogDetailService
    ) {
        $this->BlogImagesService = $BlogImagesService;
        $this->bangCapService = $bangCapService;
        $this->BlogDetailService = $BlogDetailService;
        $this->BlogService = $BlogService;
        $this->healthService = $s;
    }

    /**
     * Load màn hình BẰNG CẤP
     * 
     */
    public function index()
    {
        if(!empty($_SESSION['username'])){
            $mabn = 'mabn='.$_SESSION['username'];
            $response = Http::withBody('','application/json')->get('118.70.182.89:89/api/PACS/ViewChiDinh?'.$mabn.'');
            $response = $response->getBody()->getContents();
            $response = json_decode($response,true);
            $data = $response['results'];
            $data['benhnhan'] = $response['results']['benhnhan'];
            $data['chidinhct'] = $response['results']['chidinhct'];
            return view('Frontend::home.loadlist', $data)->render();

        }else{
            return view('Frontend::home.loadlistNotFound')->render();
        }
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
