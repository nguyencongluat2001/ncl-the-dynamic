<?php

namespace Modules\System\Category\Repositories;

use DB;
use Modules\Base\Repository;
use Modules\System\Category\Models\CateModel;

class CateRepository extends Repository
{
    public function model(){
        return CateModel::class;
    }
}
