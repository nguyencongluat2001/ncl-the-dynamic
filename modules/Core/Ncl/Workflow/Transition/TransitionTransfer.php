<?php

namespace Modules\Core\Ncl\Workflow\Transition;

use Modules\Api\Services\Admin\RecordTransitionService;
use Modules\Api\Services\Admin\UnitService;
use Modules\Api\Services\Admin\UserService;
use Illuminate\Support\Str;
use Modules\Core\Ncl\LoggerHelpers;

/**
 * Chuyển liên thông
 * 
 * @author khuongtq
 */
class TransitionTransfer
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
        $this->logger->setFileName('ChuyenLienThong');
    }

    /**
     * Thên bản ghi records_transition khi chuyển liên thông
     * 
     * @param object $record Hồ sơ
     * @param string $currentUserId ID Cán bộ hiện tại
     * @param string $nextUserId ID Cán bộ tiếp theo
     * @param string $nextUnitId ID Đơn vị tiếp theo
     * @return void
     */
    public function handle($record, $currentUserId, $nextUserId, $nextUnitId)
    {
        try {
            $dataTransition = [];
            $currentUser    = $this->userService->find($currentUserId);
            // Lấy thông tin đơn vị hiện tại
            $currentUnit = $this->unitService->find($currentUser->units_id);
            $ownerCode   = $currentUnit->code;
            $typeUnit    = $currentUnit->type_group;
            if ($currentUnit->type_group !== 'PHUONG_XA') {
                $parentCurrentUnit = $this->unitService->getUnitByCode($currentUnit->owner_code);
                $ownerCode         = $parentCurrentUnit->owner_code;
                $typeUnit          = $parentCurrentUnit->type_group;
            }
            // Lấy thông tin đơn vị liên thông
            $nextUnit            = $this->unitService->find($nextUnitId);
            $ownerTransitionCode = $nextUnit->code;
            $typeTransitionUnit  = $nextUnit->type_group;
            if ($nextUnit->type_group !== 'PHUONG_XA') {
                $parentNextUnit      = $this->unitService->getUnitByCode($nextUnit->owner_code);
                $ownerTransitionCode = $parentNextUnit->owner_code;
                $typeTransitionUnit  = $parentNextUnit->type_group;
            }
            $dataTransition = [
                'id'                    => (string) Str::uuid(),
                'record_id'             => $record->id,
                'user_id'               => $currentUserId,
                'user_transition_id'    => $nextUserId,
                'owner_code'            => $ownerCode,
                'owner_transition_code' => $ownerTransitionCode,
                'type_unit'             => $typeUnit,
                'type_transition_unit'  => $typeTransitionUnit,
                'created_at'            => date('Y-m-d H:i:s'),
                'code'                  => $record->code,
                'recordtype_id'         => $record->recordtype_id,
                'registor_name'         => $record->registor_name,
                'registor_address'      => $record->registor_address,
                'received_date'         => $record->received_date,
                'appointed_date'        => $record->appointed_date,
            ];
            $this->recordTransitionService->insert($dataTransition);
        } catch (\Exception $e) {
            $this->logger->setChannel('EXCEPTION')
                ->log('Error on TransitionTransfer::run()', $e);
        }
    }
}
