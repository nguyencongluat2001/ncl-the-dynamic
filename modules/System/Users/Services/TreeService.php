<?php

namespace Modules\System\Users\Services;

use Modules\System\Users\Helpers\TreeHelper;
use Modules\System\Users\Helpers\UserHelper;
use Modules\System\Users\Models\UnitModel;
use Modules\System\Users\Models\UserModel;

/**
 * Xử lý logic cây thư mục
 * 
 * @author luatnc
 */
class TreeService
{
    /**
     * Lấy dữ liệu cây thư mục cấp 1
     * 
     * @param array $input
     * @return array
     */
    public function getUnit(array $input): array
    {
        $unit_id = $root = '';
        $id = $input['id'];
        $root = $input['root'];
        if (isset($id) && $id !== '' && $id !== '#') {
            $unit_id = $id;
        } else {
            $unit = UnitModel::whereNull("units_id")->first();
            if ($_SESSION["role"] == 'ADMIN_SYSTEM') {
                $unit_id = $unit->id;
            } else {
                $unit_id = $_SESSION["id_unit"];
            }
        }
        return TreeHelper::zend_tree_unit($unit_id, $root);
    }

    /**
     * Lấy danh sách đơn vị và user
     * 
     * @param array $input
     * @return array
     */
    public function zendList(array $input): array
    {
        $idunit = $input['idunit'];
        $type = 'user';
        if ($idunit) {
            // lay don vi con
            $units = UnitModel::where("units_id", '=', $idunit)
                ->orderBy('order', 'asc')
                ->get(['id', 'name', 'address', 'status', 'code'])
                ->toArray();
            if ($units) {
                $type = 'unit';
                $data = $units;
            } else {
                $data = UserModel::join('position', 'users.position_id', '=', 'position.id')
                    ->where("units_id", '=', $idunit)
                    ->select(['users.id', 'users.name', 'role', 'username', 'users.order', 'address', 'users.status', 'position.code as position'])
                    ->orderBy('users.order', 'asc')
                    ->get()
                    ->toArray();
            }
        }
        $return['type'] = $type;
        $return['data'] = $data;

        return $return;
    }
}
