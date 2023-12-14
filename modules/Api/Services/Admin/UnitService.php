<?php

namespace Modules\Api\Services\Admin;

use Modules\Core\Ncl\Http\BaseService;
use Modules\Api\Repositories\Admin\UnitRepository;

class UnitService extends BaseService
{

    public function __construct()
    {
        parent::__construct();
    }

    public function repository()
    {
        return UnitRepository::class;
    }

    /**
     * Lấy tất cả đơn vị ngoại trừ quận, sở, tỉnh
     */
    public function getUnitQuanHuyenSoNganh()
    {
        return $this->repository->getUnitQuanHuyenSoNganh();
    }

    /**
     * Lấy đơn vị theo owner_code và không có type_group
     */
    public function getUnitPhongNoiVu($ownerCode)
    {
        return $this->repository->getUnitByOwnerCodeAndNotTypeGroup($ownerCode);
    }

    /**
     * Lấy đơn vị theo id
     */
    public function getUnitById($id)
    {
        return $this->repository->getUnitById($id);
    }

    /**
     * Lấy đơn vị (phường xã) theo owner_ward
     */
    public function getUnitByOwnerWard($ownerWard)
    {
        return $this->repository->getUnitByOwnerWard($ownerWard);
    }

    /**
     * Lấy đơn vị theo mã code
     * luatnc
     */
    public function getUnitByCode($code)
    {
        return $this->repository->getUnitByCode($code);
    }
    public function getDbName()
    {
        return $this->repository->where('type_group', 'QUAN_HUYEN')->orwhere('type_group', 'SO_NGHANH')->get();
    }
}
