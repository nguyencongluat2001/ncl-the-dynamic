<?php

namespace Modules\Api\Services\Admin;

use Modules\Core\Efy\Http\BaseService;
use Modules\Api\Repositories\Admin\UserRepository;
use Modules\Api\Helpers\RecordTrait;

class UserService extends BaseService
{

    use RecordTrait;

    private $unitService;

    public function __construct(UnitService $unitService)
    {
        $this->unitService = $unitService;
        parent::__construct();
    }

    public function repository()
    {
        return UserRepository::class;
    }

    public function getConnection()
    {
        $getConnection = $this->repository->getConnection();
        return $getConnection;
    }
    public function getTable()
    {
        $table = $this->repository->getTable();
        return $table;
    }

    public function getOwnerCodeByUserId($id)
    {
        $user = $this->find($id);
        $unit = $this->unitService->find($user->units_id);
        return $this->getOwnerCodeByUnit($unit);
    }
}
