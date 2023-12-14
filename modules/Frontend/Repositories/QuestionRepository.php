<?php

namespace Modules\Frontend\Repositories;

use Modules\Core\Ncl\Http\BaseRepository;
use Modules\Frontend\Models\QuestionModel;

/**
 * Repo câu hỏi
 * 
 * @author luatnc
 */
class QuestionRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function model(): string
    {
        return QuestionModel::class;
    }
}
