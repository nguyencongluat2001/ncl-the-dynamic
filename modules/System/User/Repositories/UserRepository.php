<?php

namespace Modules\System\User\Repositories;

use DB;
use Modules\Base\Repository;
use Modules\System\User\Models\UserModel;

class UserRepository extends Repository
{
    public function model(){
        return UserModel::class;
    }
}
