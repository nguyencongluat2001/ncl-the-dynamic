<?php

namespace Modules\System\Objects\Repositories;

use Illuminate\Pagination\Paginator;
use Modules\System\Objects\Models\ObjectsModel;
use Modules\Core\Ncl\Http\BaseRepository;

class ObjectsRepository extends BaseRepository
{
    private $ObjectsModel;


    public function __construct()
    {
        parent::__construct();
    }
    public function model()
    {
        return ObjectsModel::class;
    }

}
