<?php

namespace Modules\Api\Services\Admin;

use Modules\Core\Efy\Http\BaseService;
use Modules\Api\Repositories\Admin\GatewayRepository;

class GatewayService extends BaseService
{

    public function __construct()
    {
        parent::__construct();
    }

    public function repository()
    {
        return GatewayRepository::class;
    }
}
