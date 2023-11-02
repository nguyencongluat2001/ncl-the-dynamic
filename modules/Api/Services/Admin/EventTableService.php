<?php

namespace Modules\Api\Services\Admin;

use Modules\Core\Efy\Http\BaseService;
use Modules\Api\Repositories\Admin\EventTableRepository;

class EventTableService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function repository()
    {
        return EventTableRepository::class;
    }
}
