<?php

namespace Modules\Api\Repositories\Admin;

use Modules\Api\Models\Admin\ListTypeModel;
use Modules\Core\Ncl\Http\BaseRepository;

class ListTypeRepository extends BaseRepository
{
    public function model()
    {
        return ListTypeModel::class;
    }
}
