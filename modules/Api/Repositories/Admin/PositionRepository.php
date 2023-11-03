<?php

namespace Modules\Api\Repositories\Admin;

use Modules\Core\Ncl\Http\BaseRepository;
use Modules\Api\Models\Admin\PositionModel;

class PositionRepository extends BaseRepository
{
    public function model()
    {
        return PositionModel::class;
    }
}
