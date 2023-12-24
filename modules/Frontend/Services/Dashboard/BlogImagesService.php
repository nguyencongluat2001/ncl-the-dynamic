<?php

namespace Modules\Frontend\Services\Dashboard;

use Illuminate\Support\Facades\Hash;
use Modules\Base\Service;
use Modules\Frontend\Repositories\Dashboard\BlogImagesRepository;
use Str;

class BlogImagesService extends Service
{
    public function __construct()
    {
        parent::__construct();
    }

    public function repository()
    {
        return BlogImagesRepository::class;
    }

}
