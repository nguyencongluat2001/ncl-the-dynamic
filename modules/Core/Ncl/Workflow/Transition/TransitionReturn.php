<?php

namespace Modules\Core\Ncl\Workflow\Transition;

use Modules\Api\Services\Admin\RecordTransitionService;
use Modules\Api\Services\Admin\UnitService;
use Modules\Api\Services\Admin\UserService;
use Modules\Core\Ncl\LoggerHelpers;

/**
 * Liên thông trả lại
 * 
 * @author luatnc
 */
class TransitionReturn
{
    private $logger;
    private $userService;
    private $unitService;
    private $recordTransitionService;

    public function __construct(
        LoggerHelpers $loggerHelpers,
        UserService $userService,
        UnitService $unitService,
        RecordTransitionService $recordTransitionService
    ) {
        $this->logger                  = $loggerHelpers;
        $this->userService             = $userService;
        $this->unitService             = $unitService;
        $this->recordTransitionService = $recordTransitionService;
        $this->logger->setFileName('TraLaiLienThong');
    }

    /**
     * Xóa records_transition khi trả lại
     * 
     * @param object $record Hồ sơ
     * @param string $currentUserId ID Cán bộ tại
     * @param string $nextUserId ID Cán bộ tiếp theo
     * @param string $nextUnitId ID Đơn vị tiếp theo
     * @return void
     */
    public function handle($record, $currentUserId)
    {
        try {
            $currentUser        = $this->userService->find($currentUserId);
            $currentUnit        = $this->unitService->find($currentUser->units_id);
            $typeTransitionUnit = $currentUnit->type_group;
            if ($currentUnit->type_group !== 'PHUONG_XA') {
                $parentCurrentUnit  = $this->unitService->getUnitByCode($currentUnit->owner_code);
                $typeTransitionUnit = $parentCurrentUnit->type_group;
            }
            $transition = $this->recordTransitionService
                ->where('record_id', $record->id)
                ->where('type_transition_unit', $typeTransitionUnit)
                ->first();
            if ($transition) $this->recordTransitionService->delete($transition);
        } catch (\Exception $e) {
            $this->logger->setChannel('EXCEPTION')
                ->log('Error on TransitionReturn::run()', $e);
        }
    }
}
