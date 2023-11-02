<?php

namespace Modules\Api\Repositories\Admin;

use Modules\Core\Efy\Http\BaseRepository;
use Modules\Api\Models\Admin\UnitModel;

class UnitRepository extends BaseRepository
{

    public function model()
    {
        return UnitModel::class;
    }

    /**
     * Lấy tất cả đơn vị ngoại trừ quận, sở, tỉnh
     */
    public function getUnitQuanHuyenSoNganh()
    {
        return $this->model->where("type_group", '=', "QUAN_HUYEN")
            ->orWhere("type_group", '=', "SO_NGANH")
            ->orderBy('type_group', 'desc')
            ->orderBy('name')->get();
    }

    /**
     * Lấy đơn vị theo owner_code và không có type_group
     */
    public function getUnitByOwnerCodeAndNotTypeGroup($ownerCode)
    {
        return $this->model->where("owner_code", $ownerCode)
            ->whereRaw("((type_group != 'QUAN_HUYEN' and type_group != 'SO_NGANH') or type_group is null or type_group = '')")
            ->orderBy('type_group')->get();
    }

    /**
     * Lấy đơn vị theo id
     */
    public function getUnitById($id)
    {
        return $this->model->where("id", $id)->orderBy('type_group')->get();
    }

    /**
     * Lấy đơn vị (phường xã) theo owner_ward
     */
    public function getUnitByOwnerWard($ownerWard)
    {
        return $this->model->where("owner_ward", $ownerWard)->get();
    }

    public function getUnitByCode($code)
    {
        return $this->model->where('code', $code)->first();
    }

    public function getUnits($unitId)
    {
        $this->model->where(function ($query) use ($unitId) {
            $query->where('type_group', 'PHUONG_XA')
                ->orWhere('id', $unitId);
        })->get();
    }
}
