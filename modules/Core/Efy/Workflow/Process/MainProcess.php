<?php

namespace Modules\Core\Efy\Workflow\Process;

use Modules\Api\Models\Admin\PerMisionGroupModel;
use Modules\Core\Efy\Db\Connection;

trait MainProcess
{

    /**
     * Lấy đối tượng xử lý bước trước
     * 
     * @return array thông tin cán bộ
     */
    public function returnPreviewStep()
    {
        $data = $this->recordNextStepService
            ->where('next_user_id', $this->user->id)
            ->where('record_id', $this->record->id)
            ->first();
        $returns = false;
        if ($data) {
            $returns[0]['unit_id']               = $data->previous_unit_id;
            $returns[0]['unit_name']             = $data->previous_unit_name;
            $returns[0]['users'][0]['unit_id']   = $data->previous_unit_id;
            $returns[0]['users'][0]['unit_name'] = $data->previous_unit_name;
            $returns[0]['users'][0]['user_id']   = $data->previous_user_id;
            $returns[0]['users'][0]['user_name'] = $data->previous_user_name;
        }

        return $returns;
    }

    /**
     * Trả lại người nhập hồ sơ
     * 
     * @param object $gateway
     * @return array thông tin cán bộ; false: trường hợp không được phép chuyển xử lý
     */
    public function returnUserCreate($gateway)
    {
        $isTransition = $this->transitionFactory->checkTransition($this->recordType);
        // Hs không liên thông nhưng bước tiếp theo lại là liên thông -> ẩn
        if ($gateway->next_current_status == 'CHO_GUI_LIEN_THONG' && !$isTransition) return false;
        // Nếu permission_group_id=TRA_NGUOI_NHAP và trạng thái tiếp theo=CHO_GUI_LIEN_THONG -> kiểm tra xem có được chuyển liên thông không
        if (
            $gateway->next_current_status == 'CHO_GUI_LIEN_THONG' &&
            !$this->transitionFactory->checkSendTransition($this->record, $this->recordType, $this->user, $this->unit)
        ) {
            return false;
        }
        $userId = $this->record->user_id;
        if ($isTransition) {
            // Nếu tài khoản đăng nhập hiện tại dùng là đơn vị được gửi -> trả về một cửa
            if ($this->transitionFactory->checkResultTransition($this->record, $this->recordType, $this->user, $this->unit)) {
                if ($this->unit->type_group == 'PHUONG_XA') $ownerTranCode = $this->unit->code;
                else $ownerTranCode = $this->unit->owner_code;
                $trans = $this->recordTransitionService
                    ->where('record_id', $this->record->id)
                    ->where('owner_transition_code', $ownerTranCode)
                    ->first();
                if ($trans) $userId = $trans->user_transition_id;
            }
        }

        return $this->getObjectCreate($userId);
    }

    /**
     * Chuyển liên thông
     * 
     * @param object $gateway
     * @return array thông tin cán bộ; false: trường hợp không được phép chuyển xử lý
     */
    public function sendTransition($gateway)
    {
        $returns = false;
        if ($this->recordType->is_independent_of_boundary == 1) $gateway->name = 'Chuyển HS không phụ thuộc địa giới';
        if (
            $this->transitionFactory->checkSendTransition($this->record, $this->recordType, $this->user, $this->unit)
        ) {
            $returns = $this->nextUnitTransition->run($this->record, $this->user, $this->recordType);
        }

        return $returns;
    }

    /**
     * Chuyển liên thông xử lý tiếp
     * 
     * @param object $gateway
     * @return array thông tin cán bộ; false: trường hợp không được phép chuyển xử lý
     */
    public function sendTransitionContinue($gateway)
    {
        if ($this->transitionFactory->checkTransitionContinue($this->record, $this->recordType, $this->user, $this->unit)) {
            $userId = $this->continueUnitTransition->run($this->record, $this->recordType, $this->user, $this->unit);
            if ($userId) return $this->getObjectCreate($userId);
        }

        return false;
    }

    /**
     * Trả kết quả về đơn vị liên thông
     * 
     * @param object $gateway
     * @return array thông tin cán bộ; false: trường hợp không được phép chuyển xử lý
     */
    public function sendResultUnitTransition($gateway)
    {
        if (
            $this->transitionFactory->checkResultTransition($this->record, $this->recordType, $this->user, $this->unit)
        ) {
            if ($this->unit->type_group == 'PHUONG_XA') {
                $typeTransitionUnit = $this->unit->type_group;
                $ownerTransitionCode = $this->unit->code;
            } else {
                $parentUnit = $this->unitService->getUnitByCode($this->unit->owner_code);
                $typeTransitionUnit = $parentUnit->type_group;
                $ownerTransitionCode = $parentUnit->code;
            }
            $trans = $this->recordTransitionService
                ->where('record_id', $this->record->id)
                ->where('type_transition_unit', $typeTransitionUnit)
                ->where('owner_transition_code', $ownerTransitionCode)
                ->first();
            if ($trans) return $this->getObjectCreate($trans->user_id);
        }

        return false;
    }

    /**
     * Kết thúc xử lý
     * 
     * @return array thông tin cán bộ; false: trường hợp không được phép chuyển xử lý
     */
    public function sendResultToCitizen()
    {
        // Nếu hs liên thông và đang ở đvi liên thông -> không hiển thị
        // if (
        //     $this->transitionFactory->checkTransition($this->recordType) &&
        //     $this->transitionFactory->checkResultTransition($this->record, $this->recordType, $this->user, $this->unit)
        // ) {
        //     return false;
        // }
        $returns[0]['unit_id']   = 'KET_THUC';
        $returns[0]['unit_name'] = 'Lưu kết quả';
        $returns[0]['users'][0]['unit_id']   = 'KET_THUC';
        $returns[0]['users'][0]['unit_name'] = 'Lưu kết quả';
        $returns[0]['users'][0]['user_id']   = 'KET_THUC';
        $returns[0]['users'][0]['user_name'] = 'Kết thúc xử lý';

        return $returns;
    }

    /**
     * Trả kết quả
     * 
     * @return array thông tin cán bộ; false: trường hợp không được phép chuyển xử lý
     */
    public function sendResultToOneGate()
    {
        $isTransition = $this->transitionFactory->checkTransition($this->recordType);
        $userId = $this->record->user_id;
        if ($isTransition) {
            if ($this->unit->type_group == 'PHUONG_XA') {
                $typeTransitionUnit = $this->unit->type_group;
                $ownerTransitionCode = $this->unit->code;
            } else {
                $parentUnit = $this->unitService->getUnitByCode($this->unit->owner_code);
                $typeTransitionUnit = $parentUnit->type_group;
                $ownerTransitionCode = $parentUnit->code;
            }
            $trans = $this->recordTransitionService
                ->where('record_id', $this->record->id)
                ->where('type_transition_unit', $typeTransitionUnit)
                ->where('owner_transition_code', $ownerTransitionCode)
                ->first();
            if ($trans) $userId = $trans->user_transition_id;
        }

        return $this->getObjectResult($userId);
    }

    /**
     * Trả đơn vị liên thông
     * 
     * @return array thông tin cán bộ; false: trường hợp không được phép chuyển xử lý
     */
    public function returnUnitTransition()
    {
        if ($this->unit->type_group == 'PHUONG_XA') {
            $typeTransitionUnit = $this->unit->type_group;
            $ownerTransitionCode = $this->unit->code;
        } else {
            $parentUnit = $this->unitService->getUnitByCode($this->unit->owner_code);
            $typeTransitionUnit = $parentUnit->type_group;
            $ownerTransitionCode = $parentUnit->code;
        }
        $trans = $this->recordTransitionService
            ->where('record_id', $this->record->id)
            ->where('type_transition_unit', $typeTransitionUnit)
            ->where('owner_transition_code', $ownerTransitionCode)
            ->first();

        return $this->getObjectResult($trans->user_id);
    }

    /**
     * Trả kết quả thụ lý
     * 
     * @return array thông tin cán bộ; false: trường hợp không được phép chuyển xử lý
     */
    public function sendResultToHandle()
    {
        $handleId = '';
        $handUser = null;
        // $handUser = $this->recordWorkService
        //     ->where('record_id', $this->record->id)
        //     ->where(function ($query) {
        //         $query->where('work_type', 'THU_LY')
        //             ->orWhere(function ($query) {
        //                 $query->where('work_type', 'TRINH_KY')
        //                     ->whereIn('work_type_name', ['Trình lãnh đạo phòng ban', 'Trình lãnh đạo đơn vị']);
        //             });
        //     })
        //     ->orderBy('created_at', 'desc')
        //     ->first();
        $recordWorks = $this->recordWorkService
            ->where('record_id', $this->record->id)
            ->orderBy('created_at', 'desc')
            ->get();
        if ($recordWorks->isEmpty()) {
            $u = $this->unitService->getUnitByCode($this->record->owner_code);
            Connection::setConnectionTransition($u->owner_code);
            $recordWorks = $this->recordWorkTransitionService
                ->where('record_id', $this->record->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        foreach ($recordWorks as $key => $value) {
            if ($value->work_type == 'THU_LY') {
                // if (preg_match('/(Chuyển thụ lý)/', trim($value->work_type_name))) $handUser = $recordWorks[$key - 1];
                // else 
                $handUser = $value;
                break;
            } else if ($value->work_type == 'TRINH_KY' && preg_match('/(Trình lãnh đạo phòng ban)|(Trình lãnh đạo đơn vị)/', trim($value->work_type_name))) {
                $handUser = $value;
                break;
            }
        }
        if ($handUser) {
            $handleId = $handUser->user_id;
        }
        if ($handleId == '') {
            $data     = $this->recordNextStepService
                ->where('next_user_id', $this->user->id)
                ->where('record_id', $this->record->id)
                ->first();
            $handleId = $data->previous_user_id;
        }

        return $this->getObjectResult($handleId);
    }

    /**
     * Chuyển thụ lý
     * 
     * @return array thông tin cán bộ; false: trường hợp không được phép chuyển xử lý
     */
    public function sendUserHandle()
    {
        $tblPermissionGroup = (new PerMisionGroupModel())->getConnection()->getDatabaseName() . '.dbo.' . (new PerMisionGroupModel())->getTable();
        // $recordworks = $this->record->recordwork()->get();
        // foreach ($recordworks as $recordwork) {
        //     if ($recordwork->work_type == 'THU_LY') {
        //         $handleId = $recordwork->user_id;
        //     }
        // }
        // Kiem tra xem ai la nguoi thu ly chinh
        $returns = false;
        $handleId = '';
        $handUser = $this->recordWorkService
            ->where('record_id', $this->record->id)
            ->join('permission_users', 'records_work.user_id', '=', 'permission_users.users_id')
            ->join($tblPermissionGroup, 'permission_group.id', '=', 'permission_users.permission_group_id')
            ->where('permission_group.code', 'CAN_BO_THU_LY')
            ->where(function ($query) {
                $query->where('work_type', 'THU_LY')
                    ->orWhere(function ($query) {
                        $query->where('work_type', 'TRINH_KY')
                            ->whereIn('work_type_name', [
                                'Trình lãnh đạo phòng ban',
                                'Trình lãnh đạo đơn vị',
                                'Trình lãnh đạo',
                            ]);
                    });
            })
            ->orderBy('created_at', 'desc')
            ->first();
        if ($handUser) {
            $handleId = $handUser->user_id;
        }
        if ($handleId == '') {
            $data     = $this->recordNextStepService
                ->where('next_user_id', $this->user->id)
                ->where('record_id', $this->record->id)
                ->first();
            $handleId = $data->previous_user_id;
        }
        $user = $this->userService->find($handleId);
        if ($user) {
            $position = $user->position;
            $unit     = $user->unit()->first();
            $returns[0]['unit_id']   = $user->units_id;
            $returns[0]['unit_name'] = $unit->name;
            $returns[0]['users'][0]['unit_id']   = $user->units_id;
            $returns[0]['users'][0]['unit_name'] = $unit->name;
            $returns[0]['users'][0]['user_id']   = $user->id;
            $returns[0]['users'][0]['user_name'] = $position->code . ' - ' . $user->name;
        }

        return $returns;
    }


    /**
     * Chuyển xử lý cho cán bộ tiếp theo (mặc định)
     * 
     * @param object $gateway
     * @return array thông tin cán bộ; false: trường hợp không được phép chuyển xử lý
     */
    public function sendNextStep($gateway)
    {
        $returns = false;
        // $task = $this->task;
        $unit = $this->unit;
        if ($unit->type_group == 'PHUONG_XA') $ownercode = $unit->code;
        else $ownercode = $unit->owner_code;
        $permission_group_id = $gateway->permission_group_id;
        $query               = $this->permissionUserService->where('permission_group_id', $permission_group_id);
        // if ($task->process_type == 'PHONG_BAN') {
        //     $query->where('unit_id', $unit->id);
        // } else if ($task->process_type == 'TAT_CA') {
        //     // $query->where('cate', $cate);
        // } else {
            $query->where('owner_code', $ownercode);
        // }
        $groupUsers = $query->distinct()->get('users_id');
        $i           = 0;
        $arrUnits    = array();
        $arrUsers    = array();
        foreach ($groupUsers as $groupUser) {
            $user = $this->userService
                ->where('id', $groupUser->users_id)
                ->where('status', 1)
                ->first();
            if ($user) {
                $unit     = $user->unit;
                $position = $user->position;
                $arrUnits[$unit->id]['unit_id']   = $unit->id;
                $arrUnits[$unit->id]['unit_name'] = $unit->name;
                $arrUsers[$i]['unit_id']   = $unit->id;
                $arrUsers[$i]['unit_name'] = $unit->name;
                $arrUsers[$i]['user_id']   = $user->id;
                $arrUsers[$i]['unit_id']   = $unit->id;
                $arrUsers[$i]['user_name'] = $position->code . ' - ' . $user->name;
                $i++;
            }
        }
        $i = 0;
        foreach ($arrUnits as $key => $value) {
            $returns[$i]['unit_id']   = $value['unit_id'];
            $returns[$i]['unit_name'] = $value['unit_name'];
            $j = 0;
            foreach ($arrUsers as $arrUser) {
                if ($value['unit_id'] == $arrUser['unit_id']) {
                    $returns[$i]['users'][$j] = $arrUser;
                    $j++;
                }
            }
            $i++;
        }

        return $returns;
    }


    /**
     * Trong trường hợp là VPĐK QSDĐ thì xử lý ngoại lệ (Bỏ các)
     * 
     * @param object $groupUsers
     * @return array kết quả trả ra 1 mảng thông tin cán bộ; false: trường hợp không được phép chuyển xử lý
     */
    private function checkVPD($groupUsers)
    {
        $arrUnitVPD = config('moduleInitConfig.arrUnitVPD');
        if ($this->unit && array_key_exists($this->unit->code, $arrUnitVPD)) {
            $arrTemp = array();
            $i = 0;
            foreach ($groupUsers as $groupUser) {
                $user = $this->userService->where('id', $groupUser->users_id)->where('status', 1)->first();
                if (!$user) continue;
                $unit = $this->unitService->where('id', $user->units_id)->first();
                if (!$unit) continue;
                if ($this->unit->code == $unit->code || !array_key_exists($unit->code, $arrUnitVPD)) {
                    $arrTemp[$i] = $groupUser;
                    $i++;
                }
            }
            $groupUsers = $arrTemp;
        }

        return $groupUsers;
    }
}
