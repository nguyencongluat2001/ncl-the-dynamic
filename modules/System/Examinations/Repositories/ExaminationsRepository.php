<?php

namespace Modules\System\Examinations\Repositories;

use Illuminate\Pagination\Paginator;
use Modules\System\Examinations\Models\ExaminationsModel;
use Modules\Core\Ncl\Http\BaseRepository;

class ExaminationsRepository extends BaseRepository
{
    private $ExaminationsModel;


    public function __construct()
    {
        parent::__construct();
    }
    public function model()
    {
        return ExaminationsModel::class;
    }

}
