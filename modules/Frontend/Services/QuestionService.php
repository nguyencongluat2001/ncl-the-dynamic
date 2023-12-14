<?php

namespace Modules\Frontend\Services;

use Modules\Core\Ncl\Http\BaseService;
use Modules\Frontend\Repositories\QuestionRepository;

/**
 * Xử lý Câu hỏi
 * 
 * @author luatnc
 */
class QuestionService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function repository(): string
    {
        return QuestionRepository::class;
    }

    public function getRandomByContest(string $contestId): object
    {
        $count = 10;
        $questions = $this->select('id', 'ten_cau_hoi', 'dap_an_a', 'dap_an_b', 'dap_an_c', 'dap_an_d')
            ->where('dot_thi_id', $contestId)->where('trang_thai', 1)
            ->inRandomOrder()->take($count)->get();
        $questions->map(function ($q) {
            $answer = [
                ['dap_an_a' => $q->dap_an_a],
                ['dap_an_b' => $q->dap_an_b],
                ['dap_an_c' => $q->dap_an_c],
                ['dap_an_d' => $q->dap_an_d],
            ];
            shuffle($answer);
            $q->answer_random = $answer;
            unset($answer);
        });
        $questions->push([
            'id'          => 'CAU_HOI_11',
            'ten_cau_hoi' => 'Bạn dự đoán có bao nhiêu người trả lời đúng tất cả 10 câu hỏi trên?',
        ]);

        return $questions;
    }
}
