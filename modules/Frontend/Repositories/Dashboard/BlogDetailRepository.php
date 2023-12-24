<?php

namespace Modules\Frontend\Repositories\Dashboard;

use DB;
use Modules\Base\Repository;
use Modules\Frontend\Models\Dashboard\BlogDetailModel;

class BlogDetailRepository extends Repository
{

    public function model(){
        return BlogDetailModel::class;
    }
}
