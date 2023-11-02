<?php

namespace Modules\Api\Repositories\Admin;

use Modules\Core\Efy\Http\BaseRepository;
use Modules\Api\Models\Admin\EventTableModel;

class EventTableRepository extends BaseRepository
{
    public function model()
    {
        return EventTableModel::class;
    }
}
