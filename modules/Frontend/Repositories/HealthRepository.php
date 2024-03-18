<?php

namespace Modules\Frontend\Repositories;

use Modules\Core\Ncl\Http\BaseRepository;
use Modules\Frontend\Models\HealthModel;
use Modules\Base\Repository;

/**
 * Repo câu hỏi
 * 
 * @author luatnc
 */
class HealthRepository extends Repository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function model(): string
    {
        return HealthModel::class;
    }
}
