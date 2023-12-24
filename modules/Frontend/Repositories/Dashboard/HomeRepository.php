<?php

namespace Modules\Frontend\Repositories\Dashboard;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Core\Ncl\Http\BaseRepository;
use Modules\Frontend\Models\Dashboard\HomeModel;

class HomeRepository extends BaseRepository
{

    private $HomeModel;

    public function __construct()
    {
        parent::__construct();
    }
    public function model()
    {
        return HomeModel::class;
    }

    /**
     * @param array $input
     * 
     * @return array
     */
    public function _getAll($input)
    {
        return $this->HomeModel->_getAll($input);
    }
}
