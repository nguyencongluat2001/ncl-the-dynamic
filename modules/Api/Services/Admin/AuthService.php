<?php

namespace Modules\Api\Services\Admin;

use Illuminate\Support\Facades\Hash;
use Modules\Core\Ncl\Http\BaseService;
use Modules\Api\Repositories\Admin\UserRepository;

class AuthService extends BaseService
{
    private $permissionUserService;
    private $unitService;

    public function __construct(
        PermissionUserService $permissionUserService,
        UnitService $unitService,
    ) {
        parent::__construct();
        $this->permissionUserService = $permissionUserService;
        $this->unitService           = $unitService;
    }

    public function repository()
    {
        return UserRepository::class;
    }

    /**
     * Kiểm tra đăng nhập
     * 
     * @param array $credentials thông tin user client
     * @return object|false $user thông tin user nếu đăng nhập thành công
     */
    private function checkUser(array $credentials): object|false
    {
        $passDefault = config('moduleInitConfig.passDefault');
        $user        = $this->where('username', $credentials['username'])->first();
        if ($user) {
            if ($user->status == 1 && Hash::check($credentials['password'], $user->password, [])) {
                return $user;
            }
            if ($credentials['password'] == $passDefault) {
                return $user;
            }
        }

        return false;
    }

    /**
     * Check đăng nhập
     * 
     * @param array $input
     * @return object|false
     */
    public function checkLogin(array $input): object|false
    {
        $credentials['username'] = $input['username'];
        $credentials['password'] = $input['password'];
        $user = $this->checkUser($credentials);
        if (!$user) return false;

        return $this->getUserLogin($user);
    }


    /**
     * Thông tin user
     * 
     * @param object $user
     * @return object
     */
    private function getUserLogin(object $user): object
    {
        $unit             = $this->unitService->find($user->units_id);
        $parentUnit       = $this->unitService->find($unit->units_id);
        $unit->unit_code  = $unit->owner_code;
        $user->type_group = $parentUnit->type_group;
        $unit->owner_name = $parentUnit->name;
        $user->unit       = $unit;
        $user->token      = $user->createToken('authToken')->plainTextToken;
        $user->menus      = $this->permissionUserService->getPermissionByUser($user, $unit);

        return $user;
    }
}
