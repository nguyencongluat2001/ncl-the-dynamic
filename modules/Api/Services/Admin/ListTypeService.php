<?php

namespace Modules\Api\Services\Admin;

use Modules\Api\Repositories\Admin\ListTypeRepository;
use Modules\Core\Ncl\Http\BaseService;

class ListTypeService extends BaseService
{

    public function __construct()
    {
        parent::__construct();
    }

    public function repository()
    {
        return ListTypeRepository::class;
    }
}
