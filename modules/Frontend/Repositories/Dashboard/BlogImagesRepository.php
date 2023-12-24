<?php

namespace Modules\Frontend\Repositories\Dashboard;

use DB;
use Modules\Base\Repository;
use Modules\Frontend\Models\Dashboard\BlogImagesModel;

class BlogImagesRepository extends Repository
{

    public function model(){
        return BlogImagesModel::class;
    }
}
