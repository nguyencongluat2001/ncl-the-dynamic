<?php

namespace Modules\System\Users\Helpers;

use Modules\System\Users\Models\UnitModel;

/**
 * Helper hỗ trợ module user.
 *
 * @author Toanph <skype: toanph1505>
 */
class TreeHelper
{

    /**
     * Lay cac don vi con cua minh
     *
     * @return array
     */
    public static function zend_tree_unit($unit_id, $node_lv)
    {
        $_this = new self;
        $return = array();
        $column = ['id', 'name', 'code', 'address', 'status', 'order', 'type_group'];
        // lay cac don vi cap 1
        if ($unit_id !== '') {
            $units = UnitModel::where("id", $unit_id)->orderBy('order', 'asc')->get($column)->toArray();
        } else {
            $node_lv = $node_lv + 1;
            $units = UnitModel::where("units_id", NULL)->orderBy('order', 'asc')->get($column)->toArray();
            $unit_id = $units[0]['id'];
        }
        if ($units[0]['status'] == 1) {
            $return['icon'] = $_this->incon_by_root($node_lv, true, $units[0]['type_group']);
        } else {
            $return['icon'] = $_this->incon_by_root(0, true, $units[0]['type_group']);
        }

        // lay pk don vi root
        $UnitRoot = UnitModel::select('id')->whereNull('units_id')->get()->toArray();
        $pkUnitRoot = $UnitRoot[0]['id'];
        // lay tat ca don vi con cua don vi day
        $child_units = UnitModel::where("units_id", $unit_id)->orderBy('order', 'asc')->get($column)->toArray();
        if ($unit_id != $pkUnitRoot) {
            $child_units = UnitModel::where("units_id", $unit_id)->orderBy('order', 'asc')->get($column)->toArray();
        }
        $return['id'] = $units[0]['id'];
        $return['node_lv'] = $node_lv;
        $return['name'] = $units[0]['name'];
        $return['status'] = $units[0]['status'];
        $return['code'] = $units[0]['code'];
        $return['type'] = $units[0]['type_group'];
        $return['order'] = $units[0]['order'];
        $return['address'] = $units[0]['address'];
        $return['text'] = "<span class='js-tree-text'>" . $units[0]['name'] . "</span>";
        $return['a_attr'] = array('type' => 'department');
        $return['state'] = array(
            "opened" => true
        );
        $i = 0;
        $node_lv = $node_lv + 1;
        if ($child_units) {
            // lay phong ban
            foreach ($child_units as $child_unit) {
                if ($child_unit) {
                    $return['children'][$i] = $_this->create_tree_by_unit($child_unit, $node_lv);
                    $i++;
                }
            }
        }
        return $return;
    }

    public static function create_tree_by_unit($child_unit, $node_lv)
    {
        $_this = new self;
        // kiem tra don vi co phong ban con khong
        $count = UnitModel::where("units_id", $child_unit['id'])->count();
        $return['id'] = $child_unit['id'];
        $return['node_lv'] = $node_lv;
        $return['name'] = $child_unit['name'];
        $return['code'] = $child_unit['code'];
        $return['type'] = $child_unit['type_group'];
        $return['order'] = $child_unit['order'];
        $return['status'] = $child_unit['status'];
        $return['address'] = $child_unit['address'];
        $return['text'] = "<span class='js-tree-text'>" . $child_unit['name'] . "</span>";
        if ($child_unit['status'] == 1) {
            $return['icon'] = $_this->incon_by_root($node_lv, false, $child_unit['type_group']);
        } else {
            $return['icon'] = $_this->incon_by_root(0, false, $child_unit['type_group']);
        }

        if ($count > 0) {
            $opened = true;
            $return['children'] = true;
        } else {
            $return['children'] = false;
        }
        return $return;
    }

    public function incon_by_root($node_lv, $open, $type)
    {
        $icon = '';
        if ($open) {
            $status = 'close';
        } else {
            $status = 'close';
        }
        if ($node_lv == 1) {
            $icon = 'fas fa-home mfa-2x folder-lv' . $node_lv;
        } else if ($node_lv == 2) {
            $icon = 'fas fa-university folder-lv' . $node_lv;
        } else {
            if ($type == 'PHUONG_XA') {
                $icon = 'fas fa-university folder-lv' . $node_lv;
            } else {
                $icon = 'fas fa-square folder-lv' . $node_lv;
            }
        }
        return $icon;
    }

    public function get_header($node_lv)
    {
    }

    public function set_header($node_lv, $name)
    {
    }
}
