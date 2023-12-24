<?php

namespace Modules\Frontend\Repositories\Dashboard;

use DB;
use Modules\Base\Repository;
use Modules\Frontend\Models\Dashboard\CategoryModel;

class CategoryRepository extends Repository
{
    public function model(){
        return CategoryModel::class;
    }
}
