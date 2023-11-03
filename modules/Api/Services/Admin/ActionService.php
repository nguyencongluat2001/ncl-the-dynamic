<?php

namespace Modules\Api\Services\Admin;

use Modules\Core\Ncl\Http\BaseService;
use Modules\Api\Repositories\Admin\ActionRepository;

class ActionService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function repository()
    {
        return ActionRepository::class;
    }
}
