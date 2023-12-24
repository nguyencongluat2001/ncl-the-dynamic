<?php

namespace Modules\Frontend\Repositories\Dashboard;

use DB;
use Modules\Base\Repository;
use Modules\Frontend\Models\Dashboard\UserInfoModel;

class UserInfoRepository extends Repository
{

    public function model(){
        return UserInfoModel::class;
    }
}
