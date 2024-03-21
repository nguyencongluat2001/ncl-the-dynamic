<?php

namespace Modules\Frontend\Repositories\Dashboard;

use DB;
use Modules\Base\Repository;
use Modules\Frontend\Models\Dashboard\BangCapModel;

class BangCapRepository extends Repository
{
    public function model(){
        return BangCapModel::class;
    }
}
