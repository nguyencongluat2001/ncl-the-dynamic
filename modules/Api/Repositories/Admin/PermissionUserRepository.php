<?php

namespace Modules\Api\Repositories\Admin;

use Modules\Core\Ncl\Http\BaseRepository;
use Modules\Api\Models\Admin\PermissionUserModel;

class PermissionUserRepository extends BaseRepository
{
    public function model()
    {
        return PermissionUserModel::class;
    }
}
