<?php

namespace Modules\Frontend\Repositories;

use Modules\Core\Ncl\Http\BaseRepository;
use Modules\Frontend\Models\ArticlesModel;

class ArticlesRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
    }
    public function model()
    {
        return ArticlesModel::class;
    }
}