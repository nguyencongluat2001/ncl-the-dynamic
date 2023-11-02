<?php

namespace Modules\System\Examinations\Repositories;

use Illuminate\Pagination\Paginator;
use Modules\System\Examinations\Models\QuestionsModel;
use Modules\Core\Efy\Http\BaseRepository;

class QuestionsRepository extends BaseRepository
{
    private $ExaminationsModel;


    public function __construct()
    {
        parent::__construct();
    }
    public function model()
    {
        return QuestionsModel::class;
    }

}
