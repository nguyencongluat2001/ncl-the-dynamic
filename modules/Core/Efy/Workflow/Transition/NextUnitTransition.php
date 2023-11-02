<?php

namespace Modules\Core\Efy\Workflow\Transition;

use Modules\Api\Services\Admin\PerMisionGroupService;
// use Modules\Api\Services\Admin\PermisionUserCateService;
use Modules\Api\Services\Admin\UnitService;
use Modules\Api\Services\Admin\UserService;
use Modules\Api\Services\Transition\PermisionUserCateTransitionService;
use Modules\Core\Efy\Db\Connection;
use Modules\Core\Efy\Exceptions\ResponseExeption;
use Modules\Core\Efy\Workflow\WorkFlowHelper;

class NextUnitTransition
{
    private $codeRecevied = 'TIEP_NHAN_TKQ';
    private $codeHandling = 'CAN_BO_THU_LY';

    private $userService;
    private $unitService;
    private $permissionGroupService;
    // private $permissionUserCateService;
    private $permissionUserCateTransitionService;
    private $workFlowHelper;

    public function __construct(
        UserService $userService,
        UnitService $unitService,
        PerMisionGroupService $perMisionGroupService,
        // PermisionUserCateService $permisionUserCateService,
        PermisionUserCateTransitionService $permisionUserCateTransitionService,
        WorkFlowHelper $workFlowHelper
    ) {
        $this->userService                         = $userService;
        $this->unitService                         = $unitService;
        $this->permissionGroupService              = $perMisionGroupService;
        // $this->permissionUserCateService           = $permisionUserCateService;
        $this->permissionUserCateTransitionService = $permisionUserCateTransitionService;
        $this->workFlowHelper                      = $workFlowHelper;
    }

    public function run($record, $user, $recordtype)
    {
        switch ($recordtype->type_onegate) {
            case 'LIEN_THONG_XA_HUYEN':
                return $this->getNextUnitXaHuyen($record, $user, $recordtype);
            case 'LIEN_THONG_HUYEN_XA':
                return $this->getNextUnitHuyenXa($record, $user, $recordtype);
            case 'LIEN_THONG_HUYEN_SO':
                return $this->getNextUnitHuyenSo($record, $user, $recordtype);
            case 'LIEN_THONG_SO_HUYEN':
                return $this->getNextUnitSoHuyen($record, $user, $recordtype);
            case 'LIEN_THONG_SO_VPUB':
                return $this->getNextUnitSoVPUB($record, $user, $recordtype);
            case 'LIEN_THONG_XA_HUYEN_SO':
                return $this->getNextUnitXaHuyenSo($record, $user, $recordtype);
            default:
                return false;
        }
    }

    /**
     * Lấy danh sách đơn vị liên thông Xã-Huyện
     * 
     * @param $record: thông tin hồ sơ
     * @param $user: thông tin cán bộ xử lý
     * @param $recordType: thông tin thủ tục
     * @return $return đơn vị xử lý tiếp theo
     */
    public function getNextUnitXaHuyen($record, $user, $recordType)
    {
        $returns = array();
        $ward = $this->unitService->where('code', $record->owner_code)->first();
        if ($ward && $ward->owner_code) {
            $district = $this->unitService->where('code', $ward->owner_code)->first();
            if ($district) {
                $returns[0]['unit_id']   = $district->id;
                $returns[0]['unit_name'] = $district->name;
                // Lấy danh sách cán bộ tiếp nhận của Huyện 
                $perGroup = $this->permissionGroupService->where('code', $this->codeRecevied)->first();
                if (!$perGroup) return false;
                $groupUsers = $this->permissionUserCateService
                    ->where('permission_group_code', $perGroup->id)
                    ->where('cate', $recordType->cate)
                    ->where('owner_code', $district->code)
                    ->get();
                $i        = 0;
                $arrUnits = array();
                $arrUsers = array();
                foreach ($groupUsers as $groupUser) {
                    $user = $this->userService->find($groupUser->users_id);
                    if ($user) {
                        $unit                             = $user->unit;
                        $position                         = $user->position;
                        $arrUnits[$unit->id]['unit_id']   = $unit->id;
                        $arrUnits[$unit->id]['unit_name'] = $district->name . ' - ' . $unit->name;
                        $arrUsers[$i]['unit_id']          = $unit->id;
                        $arrUsers[$i]['unit_name']        = $unit->name;
                        $arrUsers[$i]['user_id']          = $user->id;
                        $arrUsers[$i]['unit_id']          = $unit->id;
                        $arrUsers[$i]['user_name']        = $position->code . ' - ' . $user->name;
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
            }
        }

        return $returns;
    }

    /**
     * Lấy danh sách đơn vị liên thông Huyện-Xã
     * 
     * @param object $record Thông tin hồ sơ
     * @param object $user Thông tin cán bộ xử lý
     * @param object $recordType Thông tin thủ tục
     * @return array Đơn vị xử lý tiếp theo
     */
    public function getNextUnitHuyenXa($record, $user, $recordType)
    {
        $returns = array();
        $district = $this->unitService->where('code', $record->owner_code)->first();
        if ($district && $district->type_group == 'QUAN_HUYEN') {
            $perGroup = $this->permissionGroupService->where('code', $this->codeRecevied)->first();
            if (!$perGroup) return false;
            $groupUsers = $this->permissionUserCateService
                ->where('permission_group_code', $perGroup->id)
                ->where('cate', $recordType->cate)
                // ->where('owner_code', $district->code)
                ->where('unit', 'PHUONG_XA')
                ->get();
            $i        = 0;
            $arrUnits = array();
            $arrUsers = array();
            foreach ($groupUsers as $groupUser) {
                $user = $this->userService->find($groupUser->users_id);
                if ($user) {
                    $unit                             = $user->unit;
                    $position                         = $user->position;
                    $arrUnits[$unit->id]['unit_id']   = $unit->id;
                    $arrUnits[$unit->id]['unit_name'] = $unit->name;
                    $arrUsers[$i]['unit_id']          = $unit->id;
                    $arrUsers[$i]['unit_name']        = $unit->name;
                    $arrUsers[$i]['user_id']          = $user->id;
                    $arrUsers[$i]['unit_id']          = $unit->id;
                    $arrUsers[$i]['user_name']        = $position->code . ' - ' . $user->name;
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
        }

        return $returns;
    }

    /**
     * Lấy danh sách đơn vị liên thông Huyện-Sở
     * 
     * @param $record: thông tin hồ sơ
     * @param $user: thông tin cán bộ xử lý
     * @param $recordType: thông tin thủ tục
     * @return $return đơn vị xử lý tiếp theo
     */
    public function getNextUnitHuyenSo($record, $user, $recordType)
    {
        $returns = array();
        if ($recordType->transition_unit !== '') {
            $perGroup = $this->permissionGroupService->where('code', $this->codeRecevied)->first();
            // Nếu là tthc không phụ thuộc địa giới lấy nhóm thụ lý
            if ($recordType->is_independent_of_boundary == 1) {
                $perGroup = $this->permissionGroupService->where('code', $this->codeHandling)->first();
            }
            if (!$perGroup) return false;
            $arrTransUnit = explode(',', $recordType->transition_unit);
            foreach ($arrTransUnit as $k => $transUnit) {
                $unitTransition = $this->unitService->where('code', $transUnit)->first();
                if (!$unitTransition) return false;
                Connection::setConnectionTransition($transUnit);
                // lay don vi lien thong
                $codeDepartment = '';
                if ($transUnit == '000.00.05.H55') {
                    $arrUnitVPD = config('moduleInitConfig.arrUnitVPD');
                    foreach ($arrUnitVPD as $key => $value) {
                        if ($value == $record->owner_code) {
                            $codeDepartment = $key;
                            break;
                        }
                    }
                }
                if ($codeDepartment !== '') {
                    $unit = $this->unitService->where('code', $codeDepartment)->first();
                    $groupUsers = $this->permissionUserCateTransitionService
                        ->where('permission_group_code', $perGroup->id)
                        ->where('cate', $recordType->cate)
                        ->where('unit_id', $unit->id)
                        ->where('owner_code', $unitTransition->code)
                        ->get();
                } else {
                    $groupUsers = $this->permissionUserCateTransitionService
                        ->where('permission_group_code', $perGroup->id)
                        ->where('cate', $recordType->cate)
                        ->where('owner_code', $unitTransition->code)
                        ->get();
                }
                $nextObject = $this->workFlowHelper->getUserByPerGroup($groupUsers, $unitTransition->name);
                if (count($nextObject) > 0) {
                    $returns[] = $nextObject[0];
                }
            }
        }

        return $returns;
    }

    /**
     * Lấy danh sách đơn vị liên thông Sở-Huyện
     * @param $record: thông tin hồ sơ
     * @param $user: thông tin cán bộ xử lý
     * @param $recordType: thông tin thủ tục
     * @return $return đơn vị xử lý tiếp theo
     */
    public function getNextUnitSoHuyen($record, $user, $recordType)
    {
        $returns    = array();
        $arrUnitVPD = config('moduleInitConfig.arrUnitVPD');
        $unit       = $this->unitService->where('id', $user->units_id)->first();
        if (array_key_exists($unit->code, $arrUnitVPD)) {
            $ownerDistrict = $arrUnitVPD[$unit->code];
            $perGroup      = $this->permissionGroupService->where('code', $this->codeRecevied)->first();
            if (!$perGroup) return false;
            $unitTransition = $this->unitService->where('code', $ownerDistrict)->first();
            if (!$unitTransition) return false;
            Connection::setConnectionTransition($ownerDistrict);
            // lay don vi lien thong
            $groupUsers = $this->permissionUserCateTransitionService
                ->where('permission_group_code', $perGroup->id)
                ->where('cate', $recordType->cate)
                ->where('owner_code', $ownerDistrict)
                ->get();
            $returns = $this->workFlowHelper->getUserByPerGroup($groupUsers, $unitTransition->name);
        } else {
            $perGroup = $this->permissionGroupService->where('code', $this->codeRecevied)->first();
            if (!$perGroup) return false;
            // dd($recordType->transition_unit);
            // if ($recordType->transition_unit !== '') {
            //     $transitionUnit  = $recordType->transition_unit;
            //     $unitTransitions = $this->unitService
            //         ->where('status', 1)
            //         ->where(function ($query) use ($transitionUnit) {
            //             $query->where('code', $transitionUnit)
            //                 ->orWhere('type_group', 'QUAN_HUYEN');
            //         })
            //         ->orderBy('code')
            //         ->get();
            // } else {
            $unitTransitions = $this->unitService
                ->where('type_group', 'QUAN_HUYEN')
                ->where('status', 1)
                ->orderBy('code')
                ->get();
            // }
            if (!$unitTransitions) return false;
            $tempData = array();
            foreach ($unitTransitions as $unitTransition) {
                // check xem có DB không (đối vs bản local)
                $existsDB = \DB::connection('sqlsrv')->table('sys.databases')->where('name', $unitTransition->owner_code)->exists();
                if ($existsDB) {
                    Connection::setConnectionTransition($unitTransition->owner_code);
                    // lay don vi lien thong
                    $groupUsers = $this->permissionUserCateTransitionService
                        ->where('permission_group_code', $perGroup->id)
                        ->where('cate', $recordType->cate)
                        ->where('owner_code', $unitTransition->code)
                        ->get();
                    $tempData = $this->workFlowHelper->getUserByPerGroup($groupUsers, $unitTransition->name);
                    $returns  = array_merge($returns, $tempData);
                }
            }
        }

        return $returns;
    }

    /**
     * Lấy danh sách đơn vị liên thông Sở-Sở
     * @param $record: thông tin hồ sơ
     * @param $user: thông tin cán bộ xử lý
     * @param $recordType: thông tin thủ tục
     * @return $return đơn vị xử lý tiếp theo
     */
    public function getNextUnitSoVPUB($record, $user, $recordType)
    {
        $returns = array();
        $unit = $this->unitService->where('id', $user->units_id)->first();
        // Nếu đơn vị hiện tại không phải VPUB và hồ sơ tạo ở đơn vị khác VPUB -> chuyển liên thông
        if (
            $unit->owner_code != '000.00.01.H55' &&
            $record->owner_code != '000.00.01.H55'
        ) {
            $perGroup = $this->permissionGroupService->where('code', $this->codeRecevied)->first();
            if (!$perGroup) return false;
            $unitTransition = $this->unitService->getUnitByCode('000.00.01.H55');
            if (!$unitTransition) return false;
            Connection::setConnectionTransition('000.00.01.H55');
            // Lấy các user tiếp nhận ở đơn vị liên thông
            $groupUsers = $this->permissionUserCateTransitionService
                ->where('permission_group_code', $perGroup->id)
                ->where('cate', $recordType->cate)
                ->where('owner_code', $unitTransition->code)
                ->get();
            $returns = $this->workFlowHelper->getUserByPerGroup($groupUsers, $unitTransition->name);
        }

        return $returns;
    }

    /**
     * Lấy danh sách đơn vị liên thông Xã-Huyện-Sở
     * 
     * @param object $record: thông tin hồ sơ
     * @param object $user: thông tin cán bộ xử lý
     * @param object $recordtype: thông tin thủ tục
     * @return array
     */
    public function getNextUnitXaHuyenSo($record, $user, $recordType)
    {
        $perGroup = $this->permissionGroupService->where('code', $this->codeRecevied)->first();
        if (!$perGroup) return false;
        $unit = $this->unitService->where('id', $user->units_id)->first();
        // Nếu user PHUONG_XA -> lấy QUAN_HUYEN
        if ($unit->type_group == 'PHUONG_XA') {
            $unitTransition = $this->unitService->where('code', $unit->owner_code)->first();
            if (!$unitTransition) return false;
            Connection::setConnectionTransition($unit->owner_code);
            // lay don vi lien thong
            $groupUsers = $this->permissionUserCateTransitionService
                ->where('permission_group_code', $perGroup->id)
                ->where('cate', $recordType->cate)
                ->where('owner_code', $unitTransition->code)
                ->get();
        } else {
            $district = $this->unitService->where('code', $unit->owner_code)->first();
            // Nếu user QUAN_HUYEN -> lấy SO_NGANH
            if ((string) $recordType->transition_unit == '') throw new ResponseExeption('Chưa cấu hình đơn vị liên thông');
            if ($district->type_group == 'QUAN_HUYEN' && $recordType->transition_unit !== '') {
                $unitTransition = $this->unitService->where('code', $recordType->transition_unit)->first();
                if (!$unitTransition) return false;
                Connection::setConnectionTransition($recordType->transition_unit);
                // lay don vi lien thong
                $codeDepartment = '';
                if ($recordType->transition_unit == '000.00.05.H55') {
                    $arrUnitVPD = config('moduleInitConfig.arrUnitVPD');
                    foreach ($arrUnitVPD as $key => $value) {
                        if ($value == $record->owner_code) {
                            $codeDepartment = $key;
                            break;
                        }
                    }
                }
                $query = $this->permissionUserCateTransitionService
                    ->where('permission_group_code', $perGroup->id)
                    ->where('cate', $recordType->cate)
                    ->where('owner_code', $unitTransition->code);
                if ($codeDepartment !== '') {
                    $unit = $this->unitService->where('code', $codeDepartment)->first();
                    $query->where('unit_id', $unit->id);
                }
                $groupUsers = $query->get();
            }
        }

        return $groupUsers ? $this->workFlowHelper->getUserByPerGroup($groupUsers, $unitTransition->name) : array();
    }
}
