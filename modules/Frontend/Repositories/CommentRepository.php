<?php

namespace Modules\Frontend\Repositories;

use Modules\Core\Efy\Http\BaseRepository;
use Modules\Frontend\Models\CommentModel;

class CommentRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
    }
    public function model()
    {
        return CommentModel::class;
    }
}