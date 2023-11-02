<?php

namespace Modules\Core\Efy\Workflow\Transition;

use Modules\Api\Services\Admin\UnitService;

class TransitionFactory
{

    private $unitService;
    private $transitionTransfer;
    private $transitionReturn;

    public function __construct(
        UnitService $unitService,
        TransitionTransfer $transitionTransfer,
        TransitionReturn $transitionReturn
    ) {
        $this->unitService        = $unitService;
        $this->transitionTransfer = $transitionTransfer;
        $this->transitionReturn   = $transitionReturn;
    }

    /**
     * Thêm thông tin vào bảng records_transition ở [ecs-h55-system] khi chuyển liên thông
     * 
     * @param object $record
     * @param string $currentUserId
     * @param string $nextUserId
     * @param string $nextUnitId
     * @return void
     */
    public function send($record, $currentUserId, $nextUserId, $nextUnitId)
    {
        return $this->transitionTransfer->handle($record, $currentUserId, $nextUserId, $nextUnitId);
    }

    /**
     * Xóa thông tin ở records_transition [ecs-h55-system] khi trả lại (!= cấp phép) đơn vị gửi liên thông
     * 
     * @param object $record
     * @param string $currentUserId
     * @return void
     */
    public function return($record, $currentUserId)
    {
        return $this->transitionReturn->handle($record, $currentUserId);
    }

    /**
     * Kiểm tra xem TTHC có phải là liên thông không
     * 
     * @param object $recordtype
     * @return bool
     */
    public function checkTransition($recordType)
    {
        if (!empty($recordType->type_onegate) && $recordType->type_onegate != 'KHONG_LIEN_THONG') {
            return true;
        }

        return false;
    }

    /**
     * Kiểm tra có được chuyển liên thông hay không
     * 
     * @param object $record thông tin hồ sơ
     * @param object $recordType thông tin thủ tục
     * @param object $user thông tin cán bộ xử lý
     * @param object $unit thông tin phòng ban xử lý
     * @return boolean
     */
    public function checkSendTransition($record, $recordType, $user, $unit)
    {
        if ($recordType->type_onegate == 'LIEN_THONG_XA_HUYEN' && $unit->type_group == 'PHUONG_XA') {
            return true;
        } else if ($recordType->type_onegate == 'LIEN_THONG_HUYEN_XA') {
            $parentUnit = $this->unitService->find($unit->units_id);
            if ($unit->type_group == '' && $parentUnit->type_group == 'QUAN_HUYEN') {
                return true;
            }
        } else if ($recordType->type_onegate == 'LIEN_THONG_SO_VPUB') {
            $parentUnit = $this->unitService->find($unit->units_id);
            if ($unit->type_group == '' && $parentUnit->type_group == 'SO_NGANH' && $parentUnit->owner_code != '000.00.01.H55') {
                return true;
            }
        } else if ($recordType->type_onegate == 'LIEN_THONG_HUYEN_SO') {
            $parentUnit = $this->unitService->find($unit->units_id);
            if ($unit->type_group == '' && $parentUnit->type_group == 'QUAN_HUYEN') {
                return true;
            }
        } else if ($recordType->type_onegate == 'LIEN_THONG_SO_HUYEN') {
            $parentUnit = $this->unitService->find($unit->units_id);
            if ($unit->type_group == '' && $parentUnit->type_group == 'SO_NGANH') {
                return true;
            }
        } else if ($recordType->type_onegate == 'LIEN_THONG_XA_HUYEN_SO') {
            if ($unit->type_group == 'PHUONG_XA') {
                return true;
            } else {
                $parentUnit = $this->unitService->find($unit->units_id);
                if ($unit->type_group == '' && $parentUnit->type_group == 'QUAN_HUYEN') {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Kiểm tra có được chuyển liên thông xử lý tiếp về đơn vị gửi liên thông
     * 
     * @param object $record Hồ sơ
     * @param object $recordType TTHC
     * @param object $user Người đăng nhập hiện tại
     * @param object $unit Đơn vị người đăng nhập
     * @return boolean
     */
    public function checkTransitionContinue($record, $recordType, $user, $unit)
    {
        $unitCreate = $this->unitService->getUnitByCode($record->owner_code);
        switch ($recordType->type_onegate) {
            case 'LIEN_THONG_XA_HUYEN':
                if ($unitCreate->type_group == 'PHUONG_XA' && empty($unit->type_group)) {
                    $parentUnit = $this->unitService->getUnitByCode($unit->owner_code);
                    if ($parentUnit->type_group == 'QUAN_HUYEN') {
                        return true;
                    }
                }
            case 'LIEN_THONG_HUYEN_XA':
                if ($unitCreate->type_group == 'QUAN_HUYEN' && $unit->type_group == 'PHUONG_XA') {
                    return true;
                }
            case 'LIEN_THONG_SO_VPUB':
                if ($unitCreate->type_group == 'SO_NGANH' && empty($unit->type_group)) {
                    $parentUnit = $this->unitService->getUnitByCode($unit->owner_code);
                    if ($parentUnit->owner_code == '000.00.01.H55') {
                        return true;
                    }
                }
            case 'LIEN_THONG_HUYEN_SO':
                if ($unitCreate->type_group == 'QUAN_HUYEN' && empty($unit->type_group)) {
                    $parentUnit = $this->unitService->getUnitByCode($unit->owner_code);
                    if ($parentUnit->type_group == 'SO_NGANH') {
                        return true;
                    }
                }
            case 'LIEN_THONG_SO_HUYEN':
                if ($unitCreate->type_group == 'SO_NGANH' && empty($unit->type_group)) {
                    $parentUnit = $this->unitService->getUnitByCode($unit->owner_code);
                    if ($parentUnit->type_group == 'QUAN_HUYEN') {
                        return true;
                    }
                }
            case 'LIEN_THONG_XA_HUYEN_SO':
                if ($unitCreate->type_group == 'PHUONG_XA' && empty($unit->type_group)) {
                    $parentUnit = $this->unitService->getUnitByCode($unit->owner_code);
                    if ($parentUnit->type_group == 'SO_NGANH' || $parentUnit->type_group == 'QUAN_HUYEN') {
                        return true;
                    }
                }

            default:
                return false;
        }

        return false;
    }

    /**
     * Kiểm tra có được trả kết quả về đơn vị liên thông hay không
     * 
     * @param object $record thông tin hồ sơ
     * @param object $recordType thông tin thủ tục
     * @param object $user thông tin cán bộ xử lý
     * @param object $unit thông tin phòng ban xử lý
     * @return boolean
     */
    public function checkResultTransition($record, $recordType, $user, $unit)
    {
        $unitCreate = $this->unitService->getUnitByCode($record->owner_code);
        switch ($recordType->type_onegate) {
            case 'LIEN_THONG_XA_HUYEN':
                if ($unitCreate->type_group == 'PHUONG_XA' && empty($unit->type_group)) {
                    $parentUnit = $this->unitService->getUnitByCode($unit->owner_code);
                    if ($parentUnit->type_group == 'QUAN_HUYEN') {
                        return true;
                    }
                }
            case 'LIEN_THONG_HUYEN_XA':
                if ($unitCreate->type_group == 'QUAN_HUYEN' && $unit->type_group == 'PHUONG_XA') {
                    return true;
                }
            case 'LIEN_THONG_SO_VPUB':
                if ($unitCreate->type_group == 'SO_NGANH' && empty($unit->type_group)) {
                    $parentUnit = $this->unitService->getUnitByCode($unit->owner_code);
                    if ($parentUnit->owner_code == '000.00.01.H55') {
                        return true;
                    }
                }
            case 'LIEN_THONG_HUYEN_SO':
                if ($unitCreate->type_group == 'QUAN_HUYEN' && empty($unit->type_group)) {
                    $parentUnit = $this->unitService->getUnitByCode($unit->owner_code);
                    if ($parentUnit->type_group == 'SO_NGANH') {
                        return true;
                    }
                }
            case 'LIEN_THONG_SO_HUYEN':
                if ($unitCreate->type_group == 'SO_NGANH' && empty($unit->type_group)) {
                    $parentUnit = $this->unitService->getUnitByCode($unit->owner_code);
                    if ($parentUnit->type_group == 'QUAN_HUYEN') {
                        return true;
                    }
                }
            case 'LIEN_THONG_XA_HUYEN_SO':
                if ($unitCreate->type_group == 'PHUONG_XA' && empty($unit->type_group)) {
                    $parentUnit = $this->unitService->getUnitByCode($unit->owner_code);
                    if ($parentUnit->type_group == 'SO_NGANH' || $parentUnit->type_group == 'QUAN_HUYEN') {
                        return true;
                    }
                }

            default:
                return false;
        }

        return false;
    }
}
