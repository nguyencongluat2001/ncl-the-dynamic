<?php

namespace Modules\Frontend\Services\Dashboard;

use Illuminate\Support\Facades\Hash;
use Modules\Base\Service;
use Modules\Frontend\Repositories\Dashboard\BangCapRepository;

use Str;

class BangCapService extends Service
{

    private $bangCapRepository;
    public function __construct(
        BangCapRepository $BangCapRepository
    ) {
        parent::__construct();
        $this->bangCapRepository = $BangCapRepository;
        parent::__construct();
    }

    public function repository()
    {
        return BangCapRepository::class;
    }
}
