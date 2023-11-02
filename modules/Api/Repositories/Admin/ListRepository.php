<?php

namespace Modules\Api\Repositories\Admin;

use Modules\Api\Models\Admin\ListModel;
use Modules\Core\Efy\Http\BaseRepository;

class ListRepository extends BaseRepository
{
    public function model()
    {
        return ListModel::class;
    }
}
