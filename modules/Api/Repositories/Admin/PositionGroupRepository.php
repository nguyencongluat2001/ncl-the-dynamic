<?php

namespace Modules\Api\Repositories\Admin;

use Modules\Core\Efy\Http\BaseRepository;
use Modules\Api\Models\Admin\PositionGroupModel;

class PositionGroupRepository extends BaseRepository
{
    public function model()
    {
        return PositionGroupModel::class;
    }
}
