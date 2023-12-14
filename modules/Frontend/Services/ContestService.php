<?php

namespace Modules\Frontend\Services;

use Modules\Core\Ncl\Http\BaseService;
use Modules\Frontend\Repositories\ContestRepository;

/**
 * Xử lý đợt thi
 * 
 * @author luatnc
 */
class ContestService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function repository(): string
    {
        return ContestRepository::class;
    }
}