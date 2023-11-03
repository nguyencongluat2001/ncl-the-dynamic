<?php

namespace Modules\Api\Repositories\Admin;

use Modules\Core\Ncl\Http\BaseRepository;
use Modules\Api\Models\Admin\WorkFlowModel;

class WorkFlowRepository extends BaseRepository
{
    public function model()
    {
        return WorkFlowModel::class;
    }
}
