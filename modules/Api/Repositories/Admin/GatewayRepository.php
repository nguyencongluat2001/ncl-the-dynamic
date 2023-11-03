<?php

namespace Modules\Api\Repositories\Admin;

use Modules\Core\Ncl\Http\BaseRepository;
use Modules\Api\Models\Admin\GatewayModel;

class GatewayRepository extends BaseRepository
{
    public function model()
    {
        return GatewayModel::class;
    }
}
