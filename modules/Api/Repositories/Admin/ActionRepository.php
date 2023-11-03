<?php

namespace Modules\Api\Repositories\Admin;

use Modules\Core\Ncl\Http\BaseRepository;
use Modules\Api\Models\Admin\ActionModel;

class ActionRepository extends BaseRepository
{
    public function model()
    {
        return ActionModel::class;
    }
}
