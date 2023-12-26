<?php

namespace Modules\Frontend\Repositories\Dashboard;

use DB;
use Modules\Base\Repository;
use Modules\Frontend\Models\Dashboard\CateModel;

class ProductRepository extends Repository
{
    public function model(){
        return CateModel::class;
    }
}
