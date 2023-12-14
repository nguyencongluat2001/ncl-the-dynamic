<?php

namespace Modules\Frontend\Repositories;

use Illuminate\Support\Str;
use Modules\Core\Ncl\Http\BaseRepository;
use Modules\Frontend\Models\ExamDetailModel;
use Modules\Frontend\Models\ExamModel;

/**
 * Repo bài thi
 * 
 * @author luatnc
 */
class ExamRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function model(): string
    {
        return ExamModel::class;
    }

    /**
     * Lưu bài thi
     * 
     * @param array $data Dữ liệu từ client
     * @param int $answerCorrect Số câu trả lời đúng
     * @param int $score Số điểm
     * @return object
     */
    public function createExam(array $data, int $answerCorrect, int $score): object
    {
        $examId = Str::uuid()->toString();
        $this->insert([
            'id'                => $examId,
            'dot_thi_id'        => $data['contest']['id'],
            'doi_tuong_id'      => $_SESSION["hoithicchc"]["id"],
            'doi_tuong_ho_ten'  => $_SESSION["hoithicchc"]["name"],
            'doi_tuong_email'   => $_SESSION["hoithicchc"]["email"],
            'doi_tuong_don_vi'  => $_SESSION["hoithicchc"]["unit"],
            'thoi_diem_nop_bai' => $data['submit_time'],
            'thoi_gian_lam_bai' => $data['exam_time'],
            'so_dap_an_dung'    => $answerCorrect,
            'diem'              => $score,
            'du_doan_so_nguoi'  => end($data['questions'])['du_doan_so_nguoi'] ?? 0,
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ]);

        return $this->find($examId);
    }

    /**
     * Cập nhật chi tiết bài thi
     * 
     * @param string $examId ID bài thi
     * @param array $questions Bộ cậu hỏi của đề thi
     * @return void
     */
    public function updateExamDetail(string $examId, array $questions): void
    {
        foreach ($questions as $key => $question) {
            if (!Str::isUuid($question['id'])) continue;
            $answerRandom = '';
            foreach ($question['answer_random'] as $arrRandom) {
                if (isset($question['dap_an_lua_chon']) && $k = array_key_exists(strtolower('dap_an_' . $question['dap_an_lua_chon']), $arrRandom)) {
                    $answerRandom = $k;
                    break;
                }
            }
            // dd($question['answer_random'], strtolower('dap_an_'.$question['dap_an_lua_chon']), $k);
            ExamDetailModel::insert([
                'bai_thi_id'    => $examId,
                'cau_hoi_id'    => $question['id'],
                'dap_an_dung'   => $question['dap_an_dung'] ?? '',
                'dap_an'        => $question['dap_an_lua_chon'] ?? '',
                'dap_an_random' => ($answerRandom == 0 ? 'A' : ($answerRandom == 1 ? 'B' : ($answerRandom == 2 ? 'C' : ($answerRandom == 3 ? 'D' : '')))),
                'json_random'   => json_encode($question['answer_random']),
                'ket_qua'       => $question['ket_qua'] ?? false,
                'thu_tu'        => $key,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
