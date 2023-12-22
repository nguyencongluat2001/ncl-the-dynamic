<?php

namespace Modules\System\Home\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Core\Ncl\Http\BaseRepository;
use Modules\System\Home\Models\HomeModel;

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
