<?php

namespace Modules\Frontend\Repositories;

use Modules\Core\Efy\Http\BaseRepository;
use Modules\Frontend\Models\SubjectModel;

/**
 * Repo đối tượng thi
 * 
 * @author khuongtq
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
