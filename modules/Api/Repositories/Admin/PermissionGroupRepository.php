<?php

namespace Modules\Api\Repositories\Admin;

use Modules\Core\Ncl\Http\BaseRepository;
use Modules\Api\Models\Admin\PermissionGroupModel;

class PermissionGroupRepository extends BaseRepository
{
    public function model()
    {
        return PermissionGroupModel::class;
    }
}
