<?php

namespace Modules\Core\Efy\Workflow;

use Modules\Api\Services\Admin\UserService;
use Modules\Api\Services\Admin\UnitService;

class WorkFlowHelper
{
    protected $userService;
    protected $unitService;

    public function __construct(
        UserService $userService,
        UnitService $unitService
    ) {
        $this->userService = $userService;
        $this->unitService = $unitService;
    }

    /**
     * Lấy danh sách đơn vị liên thông tiếp theo
     * @param $record: thông tin hồ sơ
     * @param $user: thông tin cán bộ xử lý
     * @param $recordType: thông tin thủ tục
     * @return array đơn vị xử lý tiếp theo
     */
    public function getUserByPerGroup($group_users, $ownerName = "")
    {
        $returns = array();
        if ($group_users) {
            $i = 0;
            $arrUnits = array();
            $arrUsers = array();
            foreach ($group_users as $group_user) {
                $user = $this->userService->where("id", $group_user->users_id)->where("status", 1)->first();
                if ($user) {
                    $unit = $user->unit;
                    $position = $user->position;
                    $arrUnits[$unit->id]['unit_id'] = $unit->id;
                    if ($ownerName !== "") {
                        $arrUnits[$unit->id]['unit_name'] = $ownerName . " - " . $unit->name;
                    } else {
                        $arrUnits[$unit->id]['unit_name'] = $unit->name;
                    }
                    $arrUsers[$i]['unit_id'] = $unit->id;
                    $arrUsers[$i]['unit_name'] = $unit->name;
                    $arrUsers[$i]['user_id'] = $user->id;
                    $arrUsers[$i]['unit_id'] = $unit->id;
                    $arrUsers[$i]['user_name'] = $position->code . " - " . $user->name;
                    $i++;
                }
            }
            $i = 0;
            foreach ($arrUnits as $key => $value) {
                $returns[$i]['unit_id'] = $value['unit_id'];
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
}
