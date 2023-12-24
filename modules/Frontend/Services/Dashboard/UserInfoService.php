<?php

namespace Modules\Frontend\Services\Dashboard;

use Illuminate\Support\Facades\Hash;
use Modules\Base\Service;
use Modules\Frontend\Models\Dashboard\UserModel;
use Modules\Frontend\Repositories\Dashboard\UserInfoRepository;
use Str;

class UserInfoService extends Service
{
    public function __construct()
    {
        parent::__construct();
    }

    public function repository()
    {
        return UserInfoRepository::class;
    }
    public function _update($input)
    {
        $paramInfor = [
            "company" => isset($input['company']) && !empty($input['company']) ? $input['company'] : '',
            "position" => isset($input['position']) && !empty($input['position']) ? $input['position'] : '',
            "date_join" => isset($input['date_join']) && !empty($input['date_join']) ? date('Y-m-d', strtotime($input['date_join'])) : '',
        ];
        $params = [
            "name" => isset($input['name']) && !empty($input['name']) ? $input['name'] : '',
            "email" => isset($input['email']) && !empty($input['email']) ? $input['email'] : '',
            "dateBirth" => isset($input['dateBirth']) && !empty($input['dateBirth']) ? date('Y-m-d', strtotime($input['dateBirth'])) : '',
            "phone" => isset($input['phone']) && !empty($input['phone']) ? $input['phone'] : '',
            "address" => isset($input['address']) && !empty($input['address']) ? $input['address'] : '',
        ];
        UserModel::where('id', $input['_id'])->update($params);
        $this->repository->where('user_id', $input['_id'])->update($paramInfor);
    }

}
