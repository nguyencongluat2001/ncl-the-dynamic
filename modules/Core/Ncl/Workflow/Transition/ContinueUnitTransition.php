<?php

namespace Modules\Core\Ncl\Workflow\Transition;

use Modules\Api\Services\Admin\RecordTransitionService as RecordTransitionSystemService;
use Modules\Api\Services\Admin\UnitService;

/**
 * Lấy thông tin chuyển liên thông xử lý tiếp
 * 
 * @author luatnc
 */
class ContinueUnitTransition
{
    private $unitService;
    private $recordTransitionSystemService;

    public function __construct(
        UnitService $unitService,
        RecordTransitionSystemService $recordTransitionSystemService
    ) {
        $this->unitService                   = $unitService;
        $this->recordTransitionSystemService = $recordTransitionSystemService;
    }

    public function run($record, $recordtype, $user, $unit)
    {
        switch ($recordtype->type_onegate) {
            case 'LIEN_THONG_XA_HUYEN':
            case 'LIEN_THONG_HUYEN_XA':
            case 'LIEN_THONG_HUYEN_SO':
            case 'LIEN_THONG_SO_HUYEN':
            case 'LIEN_THONG_SO_VPUB':
                return $this->getContinueTransition($record, $recordtype, $user, $unit);
                break;
            case 'LIEN_THONG_XA_HUYEN_SO':
                return $this->getContinueTransitionXaHuyenSo($record, $recordtype, $user, $unit);
                break;
            default:
                return false;
        }
    }

    /**
     * Lấy id của người chuyển liên thông
     * 
     * @param object $record
     * @param object $user
     * @param object $recordtype
     * @return string|bool
     */
    public function getContinueTransition($record, $recordtype, $user, $unit)
    {
        $tran = $this->recordTransitionSystemService
            ->where('record_id', $record->id)
            ->orderBy('created_at')
            ->first();

        if ($tran) return $tran->user_id;

        return false;
    }

    /**
     * Lấy id của người chuyển liên thông
     * 
     * @param object $record
     * @param object $user
     * @param object $recordtype
     * @return string|bool
     */
    public function getContinueTransitionXaHuyenSo($record, $recordtype, $user, $unit)
    {
        if ($unit->type_group == 'PHUONG_XA') return false;
        $parentUnit = $this->unitService->getUnitByCode($unit->owner_code);
        $q = $this->recordTransitionSystemService
            ->where('record_id', $record->id);
        if ($parentUnit->type_group == 'SO_NGANH') {
            $q->where('type_transition_unit', 'SO_NGANH');
        } else if ($parentUnit->type_group == 'QUAN_HUYEN') {
            $q->where('type_transition_unit', 'QUAN_HUYEN');
        }
        $tran = $q->orderBy('created_at')->first();
        if ($tran) return $tran->user_id;

        return false;
    }
}
