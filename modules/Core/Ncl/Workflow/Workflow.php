<?php

namespace Modules\Core\Ncl\Workflow;

use Modules\Api\Services\Admin\PermissionUserService;
use Modules\Api\Services\Admin\RecordNextStepService;
// use Modules\Api\Services\Admin\PermisionUserCateService;
use Modules\Api\Services\Admin\UnitService;
use Modules\Api\Services\Admin\UserService;
use Modules\Core\Ncl\Workflow\Transition\TransitionFactory;
use Modules\Api\Services\RecordWorkService;
use Modules\Core\Ncl\Workflow\Process\MainProcess;
use Modules\Core\Ncl\Workflow\Transition\ContinueUnitTransition;
use Modules\Core\Ncl\Workflow\Transition\NextUnitTransition;

/**
 * @author: test
 * Lớp quy trình xử lý
 */
class Workflow
{

    use MainProcess;

    private $user;
    private $unit;
    // private $cate;
    private $task;
    private $record;
    // private $recordType;

    private $permissionUserService;

    private $transitionFactory;
    private $userService;
    private $unitService;
    private $recordNextStepService;
    private $recordWorkService;
    private $nextUnitTransition;
    private $continueUnitTransition;

    public function __construct(
        PermissionUserService $permissionUserService,
        // TransitionFactory $transitionFactory,
        UserService $userService,
        // UnitService $unitService,
        // RecordNextStepService $recordNextStepService,
        // RecordWorkService $recordWorkService,
        // NextUnitTransition $nextUnitTransition,
        // ContinueUnitTransition $continueUnitTransition
    ) {
        $this->permissionUserService = $permissionUserService;
        // $this->transitionFactory           = $transitionFactory;
        $this->userService                 = $userService;
        // $this->unitService                 = $unitService;
        // $this->recordNextStepService       = $recordNextStepService;
        // $this->recordWorkService           = $recordWorkService;
        // $this->nextUnitTransition          = $nextUnitTransition;
        // $this->continueUnitTransition      = $continueUnitTransition;
    }

    /**
     * Lấy danh sách cán bộ xử lý tiếp theo, đơn vị liên thông xử lý tiếp theo....
     * 
     * @param object $task: Công việc hiện tại
     * @param object $gateways: Các bước xử lý tiếp theo
     * @param object $record: Thông tin hồ sơ
     * @param object $user: Thông tin cán bộ xử lý
     * @param object $department: Phòng ban xử lý của cán bộ
     * @return object Các bước xử lý tiếp theo + đơn vị, người thực hiện
     */
    public function getProcessByGetWay($task, $gateways, $record, $user, $department)
    {
        $this->user       = $user;
        // $this->recordType = $record->recordtype;
        $this->record     = $record;
        $this->unit       = $department;
        $this->task       = $task;
        $i                = 0;
        $nextObjects      = false;
        // dd($gateways->toArray());
        foreach ($gateways as $gateway) {
            $gateway->task_name      = $task->name;
            $gateway->task_work_type = $task->work_type;
            switch ($gateway->permission_group_id) {
                // case "TRA_LAI":
                //     $nextObjects = $this->returnPreviewStep();
                //     break;
                // case "TRA_NGUOI_NHAP":
                //     $nextObjects = $this->returnUserCreate($gateway);
                //     break;
                // case "CHUYEN_THU_LY":
                //     $nextObjects = $this->sendUserHandle($gateway);
                //     break;
                // case "CHUYEN_LIEN_THONG":
                //     $nextObjects = $this->sendTransition($gateway);
                //     break;
                // case "CHUYEN_TIEP_LIEN_THONG_XU_LY":
                //     $nextObjects = $this->sendTransitionContinue($gateway);
                //     break;
                // case "TRA_DON_VI_LIEN_THONG":
                //     $nextObjects = $this->returnUnitTransition();
                //     break;
                // case "TRA_KET_QUA_THU_LY":
                //     $nextObjects = $this->sendResultToHandle();
                //     break;
                // case "TRA_KET_QUA":
                //     $nextObjects = $this->sendResultToOneGate();
                //     break;
                // case "TRA_KET_QUA_LIEN_THONG":
                //     $nextObjects = $this->sendResultUnitTransition($gateway);
                //     break;
                case "KET_THUC":
                    $nextObjects = $this->sendResultToCitizen();
                    break;
                default:
                    $nextObjects = $this->sendNextStep($gateway);
                    break;
            }
            if ($nextObjects) {
                $gateway->nextObjects = $nextObjects;
            } else {
                unset($gateways[$i]);
            }
            $i++;
        }

        return $gateways;
    }

    /**
     * Lấy thông tin đối tượng từ mã id cán bộ
     * 
     * @param string $userId Id cán bộ
     * @return array kết quả trả ra 1 mảng thông tin cán bộ đấy
     */
    private function getObjectCreate($userId)
    {
        $user    = $this->userService->find($userId);
        $returns = array();
        if ($user) {
            $position                            = $user->position;
            $unit                                = $user->unit()->first();
            $returns[0]['unit_id']               = $user->units_id;
            $returns[0]['unit_name']             = $unit->name;
            $returns[0]['users'][0]['unit_id']   = $user->units_id;
            $returns[0]['users'][0]['unit_name'] = $unit->name;
            $returns[0]['users'][0]['user_id']   = $user->id;
            $returns[0]['users'][0]['user_name'] = $position->code . " - " . $user->name;
        }

        return $returns;
    }

    /**
     * Lấy các bước xử lý tiếp theo ở bước có kết quả (cấp phép, từ chối...)
     * 
     * @param string $userId Id cán bộ
     * @return array kết quả trả ra 1 mảng thông tin như cấp phép, từ chối....
     */
    public function getObjectResult($userId)
    {
        $configStatusResult = config('moduleInitConfig.statusResult');
        $user               = $this->userService->find($userId);
        $returns            = false;
        if ($user) {
            $position                = $user->position;
            $returns[0]['unit_id']   = $user->units_id;
            $returns[0]['unit_name'] = $position->code . " - " . $user->name;
            $i                       = 0;
            foreach ($configStatusResult as $key => $value) {
                $returns[0]['users'][$i]['unit_id']   = $user->id;
                $returns[0]['users'][$i]['unit_name'] = $user->name;
                $returns[0]['users'][$i]['user_id']   = $key;
                $returns[0]['users'][$i]['user_name'] = $value;
                $i++;
            }
        }

        return $returns;
    }
}
