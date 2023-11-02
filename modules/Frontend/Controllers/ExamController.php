<?php

namespace Modules\Frontend\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Frontend\Services\ExamService;

/**
 * Controller thông tin khi thi
 * 
 * @author khuongtq
 */
class ExamController extends Controller
{
    private $examService;

    public function __construct(
        ExamService $e
    ) {
        $this->examService = $e;
    }

    /**
     * Màn hình thi
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(string $id): View
    {
        $data = $this->examService->index($id);
        return view('Frontend::exam.index', $data);
    }

    /**
     * Lấy đề thi
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getExam(Request $request): JsonResponse
    {
        $data = $this->examService->getExam($request->input());
        return response()->json($data);
    }

    /**
     * Nộp bài thi
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submit(Request $request): JsonResponse
    {
        $result = $this->examService->submit($request->input());
        return response()->json($result);
    }

    /**
     * Nộp bài thi
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkTakenTest(Request $request): JsonResponse
    {
        if (!isset($_SESSION['hoithicchc']['id']) || $_SESSION['hoithicchc']['id'] == '')
            return response()->json(array('is_login' => false));

        $result = $this->examService->checkTakenTest($request->input());
        return response()->json($result);
    }
}
