<?php

namespace Modules\System\User\Repositories;

use DB;
use Modules\Base\Repository;
use Modules\System\User\Models\UserInfoModel;

class UserInfoRepository extends Repository
{

    public function model(){
        return UserInfoModel::class;
    }
}
