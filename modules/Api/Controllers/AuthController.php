<?php

namespace Modules\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Modules\Api\Resources\Admin\AuthResource;
use Modules\Core\Ncl\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;
use Modules\Api\Services\Admin\AuthService;
use Modules\Api\Requests\AuthRequest;

/**
 * 
 */
class AuthController extends ApiController
{

    protected $AuthService;

    public function __construct(AuthService $AuthService)
    {
        $this->AuthService = $AuthService;
    }

    /**
     * Đăng nhập
     *
     * @param \Modules\Api\Requests\AuthRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(AuthRequest $request): JsonResponse
    {
        $input = $request->all();
        $user = $this->AuthService->checkLogin($input);
        if ($user) {
            return $this->response(new AuthResource($user))->success();
        }
        return $this->response([
            'status'  => false,
            'message' => "Tên đăng nhập hoặc mật khẩu không chính xác"
        ])->success();
    }

    /**
     * Đăng xuất
     */
    public function logout(): JsonResponse
    {
        DB::table('personal_access_tokens')->whereNull('last_used_at')->delete();
        if (auth('sanctum')->check()) auth('sanctum')->user()->currentAccessToken()->delete();
        return  $this->response([
            'message' => 'Đăng xuất thành công'
        ])->success();
    }
}
