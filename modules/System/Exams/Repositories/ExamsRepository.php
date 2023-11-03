<?php

namespace Modules\System\Exams\Repositories;

use Illuminate\Pagination\Paginator;
use Modules\System\Exams\Models\ExamsModel;
use Modules\Core\Ncl\Http\BaseRepository;

class ExamsRepository extends BaseRepository
{
    private $ExamsModel;


    public function __construct()
    {
        parent::__construct();
    }
    public function model()
    {
        return ExamsModel::class;
    }

}
