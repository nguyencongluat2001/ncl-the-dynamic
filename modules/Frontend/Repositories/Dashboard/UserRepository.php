<?php

namespace Modules\Frontend\Repositories\Dashboard;

use DB;
use Modules\Base\Repository;
use Modules\Frontend\Models\Dashboard\UserModel;

class UserRepository extends Repository
{
    public function model(){
        return UserModel::class;
    }
}
