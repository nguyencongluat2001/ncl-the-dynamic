<?php

namespace Modules\Frontend\Services;

use Modules\Core\Efy\Http\BaseService;
use Modules\Frontend\Repositories\SubjectRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Xử lý đối tượng danh mục
 * 
 * @author khuongtq
 */
class SubjectService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function repository(): string
    {
        return SubjectRepository::class;
    }

    /**
     * Cập nhật mật khẩu
     * 
     * @param array $input
     * @return array
     */
    public function updatePassword(array $input): array
    {
        if (Auth::guard('frontend')->attempt(['email' => $_SESSION["hoithicchc"]['email'], 'password' => $input['password']])) {
            if ($input['new_password'] != $input['re_password']) {
                $data = array('success' => false, 'message' => 'Nhập lại mật khẩu không khớp!');
                return $data;
            }
            $user = $this->repository->where('email', $_SESSION["hoithicchc"]['email'])->first();
            if (!empty($user)) {
                $user->update(['password' => Hash::make($input['new_password'])]);
                $data = array('success' => true, 'message' => 'Đổi mật khẩu thành công!');
            } else {
                $data = array('success' => false, 'message' => 'Không tồn tại tài khoản!');
            }
        } else {
            $data = array('success' => false, 'message' => 'Mật khẩu không chính xác!');
        }

        return $data;
    }
    
}
