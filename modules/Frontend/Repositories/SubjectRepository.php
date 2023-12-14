<?php

namespace Modules\Frontend\Repositories;

use Modules\Core\Ncl\Http\BaseRepository;
use Modules\Frontend\Models\SubjectModel;

/**
 * Repo đối tượng thi
 * 
 * @author luatnc
 */
class SubjectRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function model(): string
    {
        return SubjectModel::class;
    }
}
