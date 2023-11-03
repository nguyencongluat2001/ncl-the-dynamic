<?php

namespace Modules\Frontend\Repositories;

use Modules\Core\Ncl\Http\BaseRepository;
use Modules\Frontend\Models\ContestModel;

/**
 * Repo đợt thi
 * 
 * @author khuongtq
 */
class ContestRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function model(): string
    {
        return ContestModel::class;
    }
}
