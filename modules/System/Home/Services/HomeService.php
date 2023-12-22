<?php

namespace Modules\System\Home\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Core\Ncl\Http\BaseService;
use Modules\Core\Ncl\Library;
use Modules\Core\Ncl\LoggerHelpers;
use Modules\System\Home\Models\PositionModel;
use Modules\System\Home\Models\UnitModel;
use Modules\System\Home\Models\HomeModel;
use Modules\System\Home\Repositories\HomeRepository;

/**
 * Xử lý home
 * 
 * @author luatnc
 */
class HomeService extends BaseService
{
    private $HomeRepository;
    private $logger;

    public function __construct(
        HomeRepository $u,
        LoggerHelpers $l
    ) {
        $this->HomeRepository = $u;
        $this->logger = $l;
        parent::__construct();
    }
    public function repository()
    {
        return HomeRepository::class;
    }

    /**
     * Lấy dữ liệu màn index
     * 
     * @return array
     */
    public function index(): array
    {
        $data = [];
        return $data;
    }

    /**
     * Lấy các đơn vị khi load lần đầu
     * 
     * @param array $input
     * @return array
     */
    public function loadList(array $input): array
    {
        $data['datas'] = $this->HomeRepository->_getAll($input);
        return array(
            'data' => view("Home::User.loadList", $data)->render(),
            'perPage' => $input['offset'],
        );
    }
}
