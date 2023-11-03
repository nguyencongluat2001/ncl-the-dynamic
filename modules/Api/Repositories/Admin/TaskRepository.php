<?php

namespace Modules\Api\Repositories\Admin;

use Modules\Core\Ncl\Http\BaseRepository;
use Modules\Api\Models\Admin\TaskModel;

class TaskRepository extends BaseRepository
{
    public function model()
    {
        return TaskModel::class;
    }
}
