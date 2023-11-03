<?php

namespace Modules\System\Users\Services;

use Illuminate\Support\Facades\DB;
use Modules\Core\Ncl\LoggerHelpers;
use Modules\System\Users\Models\UnitModel;
use Modules\System\Users\Repositories\UnitRepository;

/**
 * Xử lý logic đơn vị
 * 
 * @author khuongtq
 */
class DepartmentService
{
    private $unitRepository;
    private $logger;

    public function __construct(
        UnitRepository $u,
        LoggerHelpers $l
    ) {
        $this->unitRepository = $u;
        $this->logger = $l;
        $this->logger->setFileName('System_DepartmentService');
    }

    /**
     * Dữ liệu màn thêm mới đơn vị
     * 
     * @param array $input
     * @return array
     */
    public function create(array $input): array
    {
        $data['parent_id'] = $input['id'];
        $data['id'] = '';
        // kiem tra xem don vi them moi nay co la don vi trien khai hay khong
        $parentUnit = UnitModel::find($data['parent_id']);
        if (is_null($parentUnit->units_id) ||  $parentUnit->units_id == '') {
            $data['type'] = 'unit';
            $data['check_dvtk'] = "checked";
        } else {
            $data['type'] = 'department';
            $data['check_dvtk'] = "disabled";
        }
        $data['data'] = new UnitModel();
        $data['unitparent'] = $parentUnit->name;
        $data['data']['status'] = 1;
        $count = UnitModel::where('units_id', $data['parent_id'])->count();
        if ($count >= 1) {
            $count++;
        } else {
            $count = 1;
        }
        $data['data']['order'] = $count;

        return $data;
    }

    /**
     * Dữ liệu màn sửa đơn vị
     * 
     * @param array $input
     * @return array
     */
    public function edit(array $input): array
    {
        $unit = UnitModel::find($input['id']);
        $data['type'] = 'department';
        if (is_null($unit->parent->units_id) ||  $unit->parent->units_id == '') {
            $data['type'] = 'unit';
        }
        $data['data'] = $unit->toArray();
        $data['id'] = $input['id'];
        $data['parent_id'] = $unit->units_id;
        $data['unitparent'] = $unit->parent->name ?? '';
        // kiem tra xem don vi co la don vi trien khai hay khong
        $data['check_dvtk'] = "disabled";
        if ($unit->type_group == 'QUAN_HUYEN' || $unit->type_group == 'SO_NGANH') {
            $data['check_dvtk'] = "checked";
        }

        return $data;
    }

    /**
     * Update hoặc thêm mới đơn vị
     * 
     * @param array $input
     * @return array
     */
    public function update(array $input): array
    {
        $id = $input['id'];
        if ($id === '') {
            $checkcode = UnitModel::where("code", $input['code'])->exists();
            if ($checkcode) {
                return array('success' => false, 'message' => 'Mã đơn vị đã tồn tại');
            }
        }
        $params = [
            'units_id'           => $input['parent_id'],
            'type_group'         => $input['group_unit'],
            'code'               => $input['code'],
            'name'               => $input['name'],
            'address'            => $input['address'],
            'mobile'             => $input['mobile'],
            'fax'                => $input['fax'],
            'email'              => $input['email'],
            'order'              => $input['order'],
            'status' => $input['status'] === 'on' ? 1 : 0,
            'owner_code' => (isset($input['check_dvtk']) && $input['check_dvtk'] === 'on') ? $input['code'] : UnitModel::find($input['parent_id'])->owner_code,
            'owner_ward' => (isset($input['group_unit']) && $input['group_unit'] === 'PHUONG_XA') ? $input['code'] : '',
        ];
        DB::beginTransaction();
        try {
            $this->unitRepository->update($id, $params, $input['parent_id']);
            DB::commit();
            return array('success' => true, 'message' => 'Cập nhật thành công', 'parent_id' => $input['parent_id']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logger->setChannel('UPDATE_UNIT')->log('Exception: ' . $e->getMessage() . ' on file ' . $e->getFile() . ' in line ' . $e->getLine());
            return array('success' => false, 'message' => 'Cập nhật thất bại');
        }
    }

    /**
     * Xóa đơn vị
     * 
     * @param string $id
     * @return array
     */
    public function delete(string $id): array
    {
        $unit = UnitModel::find($id);
        if ($unit->children->count() > 0) {
            return array('success' => false, 'message' => 'Bạn không thể xóa đơn vị khi còn phòng ban trong đơn vị đó!');
        }
        if ($unit->users->count() > 0) {
            return array('success' => false, 'message' => 'Bạn không thể xóa đơn vị khi còn người dùng trong đơn vị đó!');
        }
        DB::beginTransaction();
        try {
            $parentId = $this->unitRepository->delete($id, $unit);
            DB::commit();
            return array('success' => true, 'message' => 'Xóa thành công', 'parent_id' => $parentId);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logger->setChannel('DELETE_UNIT')->log('Exception: ' . $e->getMessage() . ' on file ' . $e->getFile() . ' in line ' . $e->getLine());
            return array('success' => false, 'message' => 'Xóa thất bại');
        }
    }
}
