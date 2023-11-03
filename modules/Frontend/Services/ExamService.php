<?php

namespace Modules\Frontend\Services;

use Exception;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Modules\Core\Ncl\Date\DateHelper;
use Modules\Core\Ncl\Http\BaseService;
use Modules\Core\Ncl\Library;
use Modules\Frontend\Models\Admin\ListModel;
use Modules\Frontend\Models\ExamDetailModel;
use Modules\Frontend\Repositories\ExamRepository;

/**
 * Xử lý bài thi
 * 
 * @author khuongtq
 */
class ExamService extends BaseService
{

    private $contestService;
    private $questionService;

    public function __construct(
        ContestService $c,
        QuestionService $q
    ) {
        parent::__construct();
        $this->contestService = $c;
        $this->questionService = $q;
    }

    public function repository(): string
    {
        return ExamRepository::class;
    }

    /**
     * Màn hình thi
     * 
     * @param array $input
     * @return array
     */
    public function index(string $id): array
    {
        $objLibrary = new Library();
        $arrResult = array();
        $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('js', 'frontend/exam/exam.js', ',', $arrResult);
        $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('js', 'assets/chosen/chosen.jquery.min.js,assets/jquery.validate.js', ',', $arrResult);
        $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('css', 'assets/chosen/bootstrap-chosen.css', ',', $arrResult);
        $data['stringJsCss'] = json_encode($arrResult);
        $data['contestId'] = $id;

        return $data;
    }

    /**
     * Lấy đề thi
     * 
     * @param array $input
     * @return array
     */
    public function getExam(array $input): array
    {
        $contestId = $input['contest_id'];
        $contest = $this->contestService->find($contestId);
        $data['contest'] = $contest->toArray();
        $questions = $this->questionService->getRandomByContest($contestId);
        $data['questions'] = $questions->toArray();

        return $data;
    }

    /**
     * Nộp bài thi
     * 
     * @param array $input
     * @return array
     */
    public function submit(array $input): array
    {
        DB::beginTransaction();
        try {
            $questions = $input['questions'];
            $questionsByContest = $this->questionService->where('dot_thi_id', $input['contest']['id'])
                ->where('trang_thai', 1)->get()->toArray();
            $answerCorrect = 0;
            foreach ($questionsByContest as $qbc) {
                if (($k = array_search($qbc['id'], array_column($questions, 'id'))) !== false) {
                    $questions[$k]['dap_an_dung'] = $qbc['dap_an_dung'];
                    $questions[$k]['ket_qua'] = false;
                    if (isset($questions[$k]['dap_an_lua_chon']) && $questions[$k]['dap_an_lua_chon'] === $qbc['dap_an_dung']) {
                        $questions[$k]['ket_qua'] = true;
                        $answerCorrect++;
                    }
                }
            }
            $score = $answerCorrect * 10;
            $exam = $this->repository->createExam($input, $answerCorrect, $score);
            $this->repository->updateExamDetail($exam->id, $questions);
            DB::commit();

            return array('success' => true, 'message' => 'Chúc mừng! Bạn đã hoàn thành bài thi', 'score' => $score . '/100');
        } catch (Exception $e) {
            dd($e);
            return array('success' => false, 'message' => 'Xảy ra lỗi!');
        }
    }

    /**
     * Kiểm tra đối tượng hiện tại đã thi đợt thi hiện tại chưa
     * 
     * @param array $input
     * @return array
     */
    public function checkTakenTest(array $input): array
    {
        $existsExam = $this->repository->where('dot_thi_id', $input['contest_id'])
            ->where('doi_tuong_id', $_SESSION['hoithicchc']['id'])->exists();

        return array('is_taken_test' => $existsExam);
    }

    /**
     * Lấy các bài thi theo đối tượng
     * 
     * @param array $input
     * @return object
     */
    public function examBySubject(array $input): object
    {
        $numberPage = (int) $input['offset'] > 1 ? (int) $input['offset'] : 1;
        Paginator::currentPageResolver(fn () => $numberPage);
        $query = $this->repository->select([
            'bai_thi.id',
            'dot_thi.nam',
            'dot_thi.ten',
            'bai_thi.thoi_diem_nop_bai',
            'bai_thi.thoi_gian_lam_bai',
            'bai_thi.so_dap_an_dung',
            'bai_thi.diem',
            'bai_thi.du_doan_so_nguoi',
        ])
            ->join('dot_thi', 'bai_thi.dot_thi_id', '=', 'dot_thi.id')
            ->where('bai_thi.doi_tuong_id', $input['doi_tuong_id'])
            ->where('dot_thi.nam', $input['nam']);
        if (isset($input['search']) && $input['search'] != '') {
            $search = $input['search'];
            $query->where(function ($q) use ($search) {
                $q->where('bai_thi.doi_tuong_ho_ten', 'like', '%' . $search . '%')
                    ->where('bai_thi.doi_tuong_email', 'like', '%' . $search . '%')
                    ->where('bai_thi.du_doan_so_nguoi', 'like', '%' . $search . '%');
            });
        }

        return $query->orderBy('bai_thi.created_at', 'desc')->paginate((int) $input['limit'] ?? 50);
    }

    /**
     * Show chi tiết bài thi
     * 
     * @param string $examId ID bài thi
     * @return array
     */
    public function show(string $examId)
    {
        $data = array();
        $exam = $this->repository->where('id', $examId)->first();
        $exam->thoi_diem_nop_bai = DateHelper::_date($exam->thoi_diem_nop_bai, 'Y-m-d H:i:s.000', 'H:i:s d/m/Y');
        $unit = ListModel::where('code', $exam->doi_tuong_don_vi)->first();
        $tenDonVi = '';
        if ($unit) {
            $tenDonVi = $unit->name;
            $infoUnit = simplexml_load_string($unit->xml_data)->data_list;
            if ((string)$infoUnit->unit_level === 'PHUONG_XA') {
                $tenDonVi = $unit->name . ', ' . (string)$infoUnit->parent_name;
            }
        }
        $exam->doi_tuong_don_vi = $tenDonVi;
        $data['exam'] = $exam;

        // $fk_vote = $getData['id'];
        // $result   = DB::select("
        //         select a.*,c.ten_cau_hoi,c.dap_an_a,c.dap_an_b,c.dap_an_c,c.dap_an_d
        //         from bai_thi_chi_tiet a 
        //         inner join bai_thi b on a.bai_thi_id=b.id
        //         inner join cau_hoi c on a.cau_hoi_id=c.id
        //         where a.bai_thi_id='$fk_vote' order by [thu_tu] ASC");
        $examDetail = ExamDetailModel::join('bai_thi', 'bai_thi_chi_tiet.bai_thi_id', '=', 'bai_thi.id')
            ->join('cau_hoi', 'bai_thi_chi_tiet.cau_hoi_id', '=', 'cau_hoi.id')
            ->select(
                'cau_hoi.ten_cau_hoi',
                'bai_thi_chi_tiet.dap_an_dung',
                'bai_thi_chi_tiet.dap_an',
                'bai_thi_chi_tiet.dap_an_random',
                'bai_thi_chi_tiet.json_random',
                'bai_thi_chi_tiet.thu_tu',
                'bai_thi_chi_tiet.ket_qua'
            )
            ->where('bai_thi.id', $examId)
            ->get();

        // $i = 0;
        // $k = 1;
        // foreach ($result as $value) {
        //     $name_convert_1 = str_replace('<p>', '', $value->ten_cau_hoi);
        //     $name_convert_2 = str_replace('</p>', '', $name_convert_1);
        //     $result[$i]->name_convert = $name_convert_2;

        //     $listoption_convert = [
        //         ['c_ques' => 'A', 'name' => $value->dap_an_a, 'code' => 'dap_an_a', 'question_id' => $value->bai_thi_id, 'result' => $value->dap_an,],
        //         ['c_ques' => 'B', 'name' => $value->dap_an_b, 'code' => 'dap_an_b', 'question_id' => $value->bai_thi_id, 'result' => $value->dap_an,],
        //         ['c_ques' => 'C', 'name' => $value->dap_an_c, 'code' => 'dap_an_c', 'question_id' => $value->bai_thi_id, 'result' => $value->dap_an,],
        //         ['c_ques' => 'D', 'name' => $value->dap_an_d, 'code' => 'dap_an_d', 'question_id' => $value->bai_thi_id, 'result' => $value->dap_an,],
        //     ];
        //     $result[$i]->listoption = ($listoption_convert);
        //     $i++;
        //     $k++;
        // }

        $examDetail->map(function ($e) {
            $e->ten_cau_hoi = str_replace('<p>', '', str_replace('</p>', '', $e->ten_cau_hoi));
            $jsonRandom = json_decode($e->json_random, true);
            unset($e->json_random);
            $char = 65;
            foreach ($jsonRandom as $k => $question) {
                if (strtolower($e->dap_an_dung) === str_replace('dap_an_', '', array_keys($question)[0])) {
                    $e->answer_correct = chr($char);
                }
                $jsonRandom[$k]['answer_order'] = chr($char);
                $jsonRandom[$k]['answer_content'] = array_values($question)[0];
                if ($jsonRandom[$k]['answer_order'] === $e->dap_an_random) {
                    $jsonRandom[$k]['selected'] = 'checked';
                }
                $char++;
            }
            $e->questions = $jsonRandom;
        });
        $data['questions'] = $examDetail;

        return $data;
    }
}
