<?php

namespace Modules\Api\Services\Admin;

use Modules\Core\Ncl\Http\BaseService;
use Modules\Api\Repositories\Admin\PermissionUserRepository;
use Modules\Api\Models\Admin\ActionModel;
use Modules\Api\Models\Admin\ModuleModel;
use Modules\Api\Models\Admin\PermissionUserModel;

class PermissionUserService extends BaseService
{

    public function __construct()
    {
        parent::__construct();
    }

    public function repository()
    {
        return PermissionUserRepository::class;
    }

    /**
     * Lấy quyền theo User
     * 
     * @param object $user Người dùng
     * @param object $unit Đơn vị
     * @return array
     */
    public function getPermissionByUser(object $user, object $unit): array
    {
        $result = array();
        $permision = PermissionUserModel::select('permission_group.permission_full')
            ->join('permission_group', 'permission_users.permission_group_id', '=', 'permission_group.id')
            ->where('permission_users.users_id', $user->id)
            ->get();
        $arrPer = array();
        $arrModule = array();
        foreach ($permision as $per) {
            $e = \explode(",", $per->permission_full);
            foreach ($e as $key => $value) {
                if ($value !== "") {
                    $arrPer[$value] = $key;
                    if (preg_match('/^(module_)[a-zA-Z0-9\_]+$/', $value) === 1) {
                        array_push($arrModule, $value);
                    }
                }
            }
        }
        $modules = ModuleModel::where("status", 1)->whereIn('code', $arrModule)->orderBy("order", "asc")->get();
        $i = 0;
        foreach ($modules as $module) {
            $result[$i]['route'] = $module->url;
            $result[$i]['name'] = $module->name;
            $result[$i]['icon'] = $module->icon;
            $result[$i]['type'] = 'link';
            $result[$i]['children'] = false;
            $actions = ActionModel::select('code', 'name', 'url')
                ->where('status', 1)
                ->where("packet_module_id", $module->id)
                ->orderBy("order", "asc")
                ->get();
            if (sizeof($actions) > 0) {
                $result[$i]['type'] = 'sub';
                $j = 0;
                foreach ($actions as $action) {
                    if (!$this->checkIssetPermision($arrPer, $action->code)) continue;
                    $result[$i]['children'][$j] = [
                        'route' => $action->url,
                        'name' => $action->name,
                        'type' => 'link',
                    ];
                    $j++;
                }
            }
            $i++;
        }

        return  $result;
    }

    /**
     * Kiểm tra xem có quyền với  module hoặc action không
     * 
     * @param array $arrPer Các module và action
     * @param string $code Mã module hoặc action cần check
     * @return bool Returns true if it matches, otherwise returns false
     */
    private function checkIssetPermision(array $arrPer, string $code): bool
    {
        foreach ($arrPer as $key => $value) {
            if ($key === $code) {
                return true;
            }
        }

        return false;
    }

    /**
     * Lấy button cho user
     * 
     * @param string $userId
     * @return array
     */
    public function getButtonByUser(string $userId): array
    {
        $permisions = $this->select('permission_group.permission_action')
            ->join('permission_group', 'permission_group.id', '=', 'permission_users.permission_group_id')
            ->where('permission_users.users_id', $userId)
            ->get();
        $strTemp = '';
        $permisions->map(function ($permision) use (&$strTemp) {
            $strTemp .= "," . $permision->permission_action;
        });

        return explode(",", trim($strTemp, ",")) ?? [];
    }
}
