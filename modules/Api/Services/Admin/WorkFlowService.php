<?php

namespace Modules\Api\Services\Admin;

use Modules\Core\Ncl\Http\BaseService;
use Modules\Api\Repositories\Admin\WorkFlowRepository;

class WorkFlowService extends BaseService
{

    public function __construct()
    {
        parent::__construct();
    }

    public function repository(){
        return WorkFlowRepository::class;
      }

}
