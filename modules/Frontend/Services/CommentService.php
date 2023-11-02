<?php

namespace Modules\Frontend\Services;

use Modules\Core\Efy\Http\BaseService;
use Modules\Frontend\Repositories\CommentRepository;

class CommentService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }
    public function repository()
    {
        return CommentRepository::class;
    }
}