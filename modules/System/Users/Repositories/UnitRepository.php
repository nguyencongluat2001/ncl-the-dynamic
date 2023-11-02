<?php

namespace Modules\System\Users\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\System\Users\Models\UnitModel;

class UnitRepository
{
    private $unitModel;

    public function __construct(UnitModel $u)
    {
        $this->unitModel = $u;
    }

    /**
     * Cập nhật hoặc thêm mới đơn vị
     * 
     * @param string $id
     * @param array $data
     * @return bool
     */
    public function update(string $id, array $data, string $parentId): bool
    {
        if ($id !== '') {
            $unit = $this->unitModel->find($id);
            $unit->updated_at = date('Y-m-d H:i:s');
        } else {
            $id = Str::uuid()->toString();
            $unit = new $this->unitModel;
            $unit->id = $id;
            $unit->created_at = date('Y-m-d H:i:s');
        }
        $unit->units_id   = $data['units_id'];
        $unit->type_group = $data['type_group'];
        $unit->code       = $data['code'];
        $unit->name       = $data['name'];
        $unit->address    = $data['address'];
        $unit->mobile     = $data['mobile'];
        $unit->fax        = $data['fax'];
        $unit->email      = $data['email'];
        $unit->order      = $data['order'];
        $unit->status     = $data['status'];
        $unit->owner_code = $data['owner_code'];
        $unit->owner_ward = $data['owner_ward'];
        $unit->save();
        $this->updateOrder($id, $parentId, $data['order']);

        return true;
    }

    /**
     * Xóa đơn vị
     * 
     * @param string @id
     * @param object $unit
     * @return string
     */
    public function delete(string $id, object $unit): string
    {
        $parentId = $unit->units_id;
        $order = $unit->order;
        $unit->delete();
        $this->updateOrder($id, $parentId, $order);

        return $parentId;
    }

    /**
     * Update lại số thứ tự
     * 
     * @param string $id
     * @param string $parentId
     * @param int|string $order
     * @return void
     */
    public function updateOrder(string $id, string $parentId, int|string $order): void
    {
        $units = $this->unitModel->where("order", ">=", $order)
            ->where("units_id", $parentId)
            ->where("id", '<>', $id)
            ->orderBy('order', 'asc')
            ->get(['id', 'order'])
            ->toArray();
        $i = $order;
        foreach ($units as $unit) {
            $i++;
            $this->unitModel->find($unit['id'])->update(['order' => $i]);
        }
    }

    











    // tạo hàm  lấy theo cấp đơn vị
    public function getByUnits($unit)
    {
        if ($unit !== '') {
            return $this->unitModel::select("*")->where('type_group', $unit)->get();
        } else {
            return $this->unitModel;
        }
    }

    // hàm kiểm có đơn vị con hay không
    public function getDepartmentByUnitId($unitId)
    {
        if ($unitId) {
            return $this->unitModel::where('units.units_id', $unitId)
                ->where(DB::raw('(select count(*) from users where units_id = units.id)'), '>', 0)
                ->get();
        } else {
            return $this->unitModel;
        }
    }

    public function getDepartmentByDistrict($unit_id)
    {

        if ($unit_id) {
            return $this->unitModel::where("units_id", $unit_id)
                ->where(function ($query) {
                    $query->where('type_group', '=', '')
                        ->orWhereNull('type_group');
                })
                ->where(DB::raw('(select count(*) from users where units_id = units.id)'), '>', 0)
                ->get();
        } else {
            return $this->unitModel;
        }
    }

    public function getDepartmentByWard($unit_id)
    {
        if ($unit_id) {
            return $this->unitModel::where("units_id", $unit_id)->where("type_group", "PHUONG_XA")
                ->where(DB::raw('(select count(*) from users where units_id = units.id)'), '>', 0)
                ->get();
        } else {
            return $this->unitModel;
        }
    }
}
