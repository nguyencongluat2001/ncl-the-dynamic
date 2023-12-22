<?php

namespace Modules\System\Sql\Repositories;

use Modules\Base\Repository;
use Modules\System\Sql\Models\SqlModel;

class SqlRepository extends Repository
{
    public function model(){
        return SqlModel::class;
    }
}
