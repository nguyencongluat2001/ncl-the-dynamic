<?php

namespace Modules\Frontend\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Frontend\Services\Dashboard\HomeService;

/**
 * Lớp xử lý quản trị phòng ban
 *
 * @author Luatnc
 */
class HomeController extends Controller
{
    private $HomeService;

    public function __construct(HomeService $d)
    {
        $this->HomeService = $d;
    }
    public function index(Request $request)
    {
        $data = $this->HomeService->index();
        return view("Frontend::Dashboard.home.index",$data);
    }

   
}
