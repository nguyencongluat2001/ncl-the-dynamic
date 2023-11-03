<?php

namespace Modules\Frontend\Services;

use Modules\Core\Ncl\Date\DateHelper;
use Modules\Frontend\Services\Admin\ListService;

/**
 * HomeService
 *
 * @author Toanph <skype: toanph1505>
 */
class HomeService
{
    private $contestService;
    private $examService;
    private $listService;

    public function __construct(
        ContestService $c,
        ExamService $e,
        ListService $l,
    ) {
        $this->contestService = $c;
        $this->examService = $e;
        $this->listService = $l;
    }

    public function getBanner()
    {
    }

    /**
     * Lấy dữ liệu trang chủ
     * 
     * @return array
     */
    public function getData(): array
    {
        $data  = array();
        $text1 = '';
        $text2 = '';

        $contests = $this->contestService->select('id', 'ten', 'nam', 'ngay_bat_dau', 'ngay_ket_thuc', 'trang_thai', 'thoi_gian_lam_bai')
            ->where('nam', date('Y'))->where('trang_thai', 1)->orderBy('created_at')->get()->toArray();
        if (isset($contests[0]['ngay_bat_dau']) && $contests[0]['ngay_bat_dau'] != '') {
            $startDate = DateHelper::_date($contests[0]['ngay_bat_dau'], 'Y-m-d H:i:s.000', 'd/m/Y');
            $endDate   = DateHelper::_date(end($contests)['ngay_ket_thuc'], 'Y-m-d H:i:s.000', 'd/m/Y');
            $text1     = "Thời gian diễn ra từ ngày $startDate đến ngày $endDate";
        }
        $data['text1']  = $text1 ?: 'Chưa diễn ra cuộc thi';
        $currentDate    = date('Y-m-d');
        $currentContest = array();
        $checkContest = true;
        $indexContestForRank = null;
        foreach ($contests as $key => $contest) {
            $d1 = $contest['ngay_bat_dau'];
            $d2 = $contest['ngay_ket_thuc'];
            $contests[$key]['ngay_bat_dau'] = DateHelper::_date($contest['ngay_bat_dau'], 'Y-m-d H:i:s.000', 'd/m/Y');
            $contests[$key]['ngay_ket_thuc'] = DateHelper::_date($contest['ngay_ket_thuc'], 'Y-m-d H:i:s.000', 'd/m/Y');
            if ($checkContest) {
                if (strtotime($currentDate) > strtotime($d2)) {
                    $text2 = trim($contest['ten']) . " đã kết thúc";
                    $indexContestForRank = $key;
                } else if (strtotime($d1) <= strtotime($currentDate) && strtotime($currentDate) <= strtotime($d2)) {
                    $text2 = trim($contest['ten']) . " kết thúc sau";
                    $indexContestForRank = $key;
                    $currentContest = $contest;
                    $checkContest = false;
                }
            }
        }
        $data['text2'] = $text2 ?: 'Chưa diễn ra cuộc thi';
        if (count($currentContest) > 0) {
            $duration = strtotime(DateHelper::_date($currentContest['ngay_ket_thuc'], 'Y-m-d H:i:s.000', 'Y-m-d') . ' 23:59:59') - strtotime(date('Y-m-d H:i:s'));
        }
        $data['duration'] = $duration ?? 0;
        $data['contest']  = $currentContest;
        $data['contests'] = $contests ?: array();
        $data['index_contest_for_rank'] = $indexContestForRank;

        return $data;
    }

    /**
     * Lấy thông tin Bảng xếp hạng
     * 
     * @param string $contestId ID đợt thi
     * @return array
     */
    public function getRank(string $contestId): array
    {
        $data = array();
        $contest = $this->contestService->find($contestId);
        // dd($contest);
        $type = 0;
        $startDate = DateHelper::_date($contest->ngay_bat_dau, 'Y-m-d H:i:s.000', 'Y-m-d');
        $endDate   = DateHelper::_date($contest->ngay_ket_thuc, 'Y-m-d H:i:s.000', 'Y-m-d');
        if (strtotime($startDate) <= strtotime(date('Y-m-d')) && strtotime(date('Y-m-d')) <= strtotime($endDate)) {
            $type = 1;
        } else if (strtotime($startDate) >= strtotime(date('Y-m-d')) && strtotime(date('Y-m-d')) <= strtotime($endDate)) {
            $type = 2;
        }
        $list = $this->listService->getAllUnits();
        $p = $this->rankPersonal($contestId, $list, $type);
        $data['total'] = $p['total'];
        $data['personal'] = $p['view'];
        $data['group'] = $this->rankGroup($contestId, $list);

        return $data;
    }

    /**
     * Cá nhân
     * 
     * @param string $contestId ID đọt thi
     * @param object $list Ds các đơn vị
     * @param int $type Check đợt thi đã kết thúc `0`, đang diễn ra `1` hay chưa diễn ra `2`
     * @return array
     */
    private function rankPersonal(string $contestId, object $list, int $type): array
    {
        if ($type == 2) return ['view' => '', 'total' => 0];
        $select = "
            dot_thi.ten as ten_dot_thi,
            bai_thi.doi_tuong_ho_ten,
            bai_thi.doi_tuong_email,
            bai_thi.doi_tuong_don_vi,
            bai_thi.diem,
            bai_thi.thoi_gian_lam_bai,
            bai_thi.du_doan_so_nguoi,
            bai_thi.so_dap_an_dung";
        if ($type == 1) {
            $select = "
            dot_thi.ten as ten_dot_thi,
            bai_thi.doi_tuong_ho_ten,
            bai_thi.doi_tuong_email,
            bai_thi.doi_tuong_don_vi,
            '?' as diem,
            '?' thoi_gian_lam_bai,
            '?' du_doan_so_nguoi,
            '?' so_dap_an_dung";
        }
        $personal = $this->examService
            ->where('dot_thi.id', $contestId)
            ->selectRaw($select)
            ->join('dot_thi', 'bai_thi.dot_thi_id', '=', 'dot_thi.id');
        if ($type == 0) $personal->orderBy('diem', 'desc');
        $personal = $personal->orderBy('thoi_gian_lam_bai', 'asc')->get();
        $total = $personal->count() ?: 0;
        $number = $personal->where('diem', '100')->count(); // Số ng trả lời đúng 10 câu
        $personal = $personal->toArray();
        // sắp xếp
        if ($type == 0) {
            usort($personal, function ($a, $b) use ($number) {
                if ($a['diem'] == $b['diem']) {
                    if ($a['thoi_gian_lam_bai'] == $b['thoi_gian_lam_bai']) {
                        return abs($a['du_doan_so_nguoi'] - $number) < abs($b['du_doan_so_nguoi'] - $number) ? -1
                            : (abs($a['du_doan_so_nguoi'] - $number) > abs($b['du_doan_so_nguoi'] - $number) ? 1 : 0);
                    }
                    return $a['thoi_gian_lam_bai'] - $b['thoi_gian_lam_bai'];
                }
                return $b['diem'] - $a['diem'];
            });
        }

        $personal = array_slice($personal, 0, 10);

        foreach ($personal as $key => $value) {
            $unit = $list->where('code', $value['doi_tuong_don_vi'])->first();
            if ($unit->unit_level === 'PHUONG_XA') {
                $personal[$key]['ten_don_vi'] = $unit->name . ' - ' . $unit->parent_name;
            } else $personal[$key]['ten_don_vi'] = $unit->name;
        }

        return [
            'view' => (string)view('Frontend::home.rank.personal', ['personals' => $personal, 'total' => $type == 0 ? $number : '?']),
            'total' => $total
        ];
    }

    /**
     * Tập thể
     * 
     * @param string $contestId ID đọt thi
     * @param object $list Ds các đơn vị
     * @return string
     */
    private function rankGroup(string $contestId, object $list): string
    {
        $group = $this->examService
            ->where('dot_thi_id', $contestId)->selectRaw('doi_tuong_don_vi, count(id) as so_thi_sinh')
            ->groupBy('doi_tuong_don_vi')
            ->orderByRaw('count(id) desc')->get()->toArray();

        $group = array_slice($group, 0, 10);

        foreach ($group as $key => $value) {
            $unit = $list->where('code', $value['doi_tuong_don_vi'])->first();
            if ($unit->unit_level === 'PHUONG_XA') {
                $group[$key]['ten_don_vi'] = $unit->name . ' - ' . $unit->parent_name;
            } else $group[$key]['ten_don_vi'] = $unit->name;
        }

        return (string)view('Frontend::home.rank.group', ['groups' => $group]);
    }
}
