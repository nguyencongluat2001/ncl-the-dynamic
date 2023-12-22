<?php

namespace Modules\System\Category\Repositories;

use DB;
use Modules\Base\Repository;
use Modules\System\Category\Models\CategoryModel;

class CategoryRepository extends Repository
{
    public function model(){
        return CategoryModel::class;
    }
}
