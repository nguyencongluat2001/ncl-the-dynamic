<?php

namespace Modules\Api\Repositories\Admin;

use Modules\Core\Ncl\Http\BaseRepository;
use Modules\Api\Models\Admin\UserModel;

class UserRepository extends BaseRepository
{
    public function model()
    {
        return UserModel::class;
    }

    public function getConnection()
    {
        $getConnection = $this->model->getConnection()->getDatabaseName();
        return $getConnection;
    }

    public function getTable()
    {
        $table = $this->model->getTable();
        return $table;
    }

    public function getUserByUnitAndRecord($input)
    {
        return $this->model
            // ->join('records as b', 'users.id', '=', 'b.user_id')
            ->where('units_id', $input['unit'])
            ->get();
    }
}
