<?php

namespace Modules\Frontend\Repositories\Dashboard;

use DB;
use Modules\Base\Repository;
use Modules\Frontend\Models\Dashboard\BlogModel;

class BlogRepository extends Repository
{

    public function model(){
        return BlogModel::class;
    }
}
