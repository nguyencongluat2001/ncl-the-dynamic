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
        return $data;
    }
}
