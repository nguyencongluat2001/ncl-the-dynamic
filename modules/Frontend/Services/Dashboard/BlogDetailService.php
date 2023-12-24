<?php

namespace Modules\Frontend\Services\Dashboard;

use Illuminate\Support\Facades\Hash;
use Modules\Base\Service;
use Modules\Frontend\Repositories\Dashboard\BlogDetailRepository;
use Str;

class BlogDetailService extends Service
{
    public function __construct()
    {
        parent::__construct();
    }

    public function repository()
    {
        return BlogDetailRepository::class;
    }

}
