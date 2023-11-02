<?php

namespace Modules\Frontend\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Frontend\Services\ArticlesService;

class NotificationController extends Controller
{
    private $articlesService;

    function __construct(ArticlesService $articlesService) {
        $this->articlesService = $articlesService;
    }
    /**
     * Trang index
     * @return view
     */
    public function index() {
        $data = $this->articlesService->index();
        return view('Frontend::notification.index', $data);
    }
    /**
     * Tranh danh sách
     * @return view
     */
    public function list() {
        $data = $this->articlesService->list();
        return view('Frontend::notification.list', $data);
    }
    /**
     * Thông tin bài viết
     * @return view
     */
    public function detail(Request $request, $slug)
    {
        $data = $this->articlesService->_detail($request->all(), $slug);
        return view('Frontend::notification.detail', $data);
    }
}