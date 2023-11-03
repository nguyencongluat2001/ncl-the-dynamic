<?php

namespace Modules\Api\Repositories\Admin;

use Modules\Core\Ncl\Http\BaseRepository;
use Modules\Api\Models\Admin\ModuleModel;

class ModuleRepository extends BaseRepository
{
    public function model()
    {
        return ModuleModel::class;
    }
}
