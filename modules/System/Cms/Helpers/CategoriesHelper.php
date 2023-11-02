<?php

namespace Modules\System\Cms\Helpers;

use DB;
use Modules\System\Cms\Models\UserModel;
use Modules\System\Cms\Models\CategoriesModel;

/**
 * Helper hỗ trợ module user.
 *
 * @author Toanph <skype: toanph1505>
 */
class CategoriesHelper {

    public $child_units = array();

    /**
     * Tạo cây thư mục phòng ban, người sử dụng
     *
     * @param string $module : module của menu
     * @param string $menu : menu cha
     * @param boolean $active : là true là active trên form
     * @param string $parrenturl : url parent menu
     *
     * @return string html
     */
    public static function create_tree($unit_user, $root = false, $Position, $user_name, $checkdepartment) {
        //dd($unit_user);
        $return['id'] = $unit_user['id'];
        $return['text'] = $unit_user['name'];
        $return['a_attr'] = array('type' => 'department');
        if ($root) {
            if (!$checkdepartment) {
                $return['icon'] = 'fa fa-home fa-2x';
            }
            $opened = true;
        } else {
            $opened = false;
        }
        $return['data'] = array();
        $return['state'] = array(
            "opened" => $opened
        );

        $UserModel = new UserModel();
        // lay nguoi su dung trong phong ban
        $users = $UserModel->where("parent_id", $unit_user['id']);
        if ($Position <> '') {
            $users = $users->where('FK_POSITION', $Position);
        }
        if (!empty($user_name)) {
            $users = $users->where('C_USERNAME', 'like', '% ' . $user_name . '%');
        }
        $users = $users->orderBy('C_ORDER', 'asc')->get()->toArray();
        if (count($users) > 0) {
            $i = 0;
            $arrUsers = array();
            foreach ($users as $user) {
                if ($user['C_ROLE'] == 'ADMIN_SYSTEM') {
                    $name = "<span style='font-weight: bold;'>" . $user['name'] . "</span>";
                    $arrUsers[$i]['icon'] = 'fa fa-users fa-hight';
                } else if ($user['C_ROLE'] == 'ADMIN_SYSTEM') {
                    $name = "<span style='color: blue'>" . $user['name'] . "</span>";
                    $arrUsers[$i]['icon'] = 'fa fa-users fa-hight';
                } else {
                    $name = "<span style='color: black'>" . $user['name'] . "</span>";
                    $arrUsers[$i]['icon'] = 'fa fa-user fa-hight';
                }
                $arrUsers[$i]['id'] = $user['PK_STAFF'];
                // lay quyen lien quan
                $arrUsers[$i]['text'] = $name;

                $arrUsers[$i]['a_attr'] = array('type' => 'user');
                $i++;
            }
            $return['children'] = $arrUsers;
        }
        // kiem tra xem phong ban co cap con hay khong
        $childrens = UnitModel::where("parent_id", $unit_user['id'])->orderBy('C_ORDER', 'asc')->get()->toArray();
        if (count($childrens) > 0) {
            $j = 0;
            foreach ($childrens as $children) {
                $return['children'][$j] = CategoriesHelper::create_tree($children, false, $Position, $user_name, $checkdepartment);
                $j++;
            }
        }
        return $return;
    }

    /**
     * Lấy cây thư mục người dùng và phong ban
     *
     * @param
     *
     * @return array()
     */
    public static function get_tree_user_unit($department, $Position, $user_name) {
        // lay cac don vi cap 1
        if ($department !== '') {
            $units = UnitModel::where("id", $department)->orderBy('C_ORDER', 'asc')->get()->toArray();
            $checkdepartment = true;
        } else {
            $checkdepartment = false;
            $units = UnitModel::where("parent_id", NULL)->orderBy('C_ORDER', 'asc')->get()->toArray();
        }

        $return = array();
        $i = 0;
        foreach ($units as $unit) {
            if ($unit) {
                $return[$i] = CategoriesHelper::create_tree($unit, true, $Position, $user_name, $checkdepartment);
                $i++;
            }
        }
        return $return;
    }

    /**
     * Lấy cây thư mục người dùng và phong ban theo dạng checkbox
     *
     * @param
     *
     * @return array()
     */
    public static function get_tree_user_unit_checkbox($arr_sfaff_group) {
        // lay cac don vi cap 1
        $units = UnitModel::where("parent_id", NULL)->orderBy('C_ORDER', 'asc')->get()->toArray();
        $return = array();
        $i = 0;
        foreach ($units as $unit) {
            if ($unit) {
                $return[$i] = CategoriesHelper::create_tree_checkbox($arr_sfaff_group, $unit, true);
                $i++;
            }
        }
        return $return;
    }

    /**
     * Tạo cây thư mục phòng ban, người sử dụng
     *
     * @param string $module : module của menu
     * @param string $menu : menu cha
     * @param boolean $active : là true là active trên form
     * @param string $parrenturl : url parent menu
     *
     * @return string html
     */
    public static function create_tree_checkbox($arr_sfaff_groups, $unit_user, $root = false) {
        $return['id'] = $unit_user['id'];
        $return['text'] = $unit_user['name'];
        // lay nguoi su dung trong phong ban
        $users = UserModel::where("unit_id", $unit_user['id'])->orderBy('order', 'asc')->get()->toArray();
        if (count($users) > 0) {
            $i = 0;
            $arrUsers = array();
            foreach ($users as $user) {
                $checkslecd = false;
                $arrUsers[$i]['id'] = $user['id'];
                $arrUsers[$i]['text'] = $user['name'];
                if ($arr_sfaff_groups) {
                    foreach ($arr_sfaff_groups as $arr_sfaff_group) {
                        if ($arr_sfaff_group['user_id'] == $user['id']) {
                            $checkslecd = true;
                        }
                    }
                }
                if ($checkslecd) {
                    $arrUsers[$i]['state'] = array('selected' => true);
                }
                $i++;
            }
            $return['children'] = $arrUsers;
        }
        // kiem tra xem phong ban co cap con hay khong
        $childrens = UnitModel::where("parent", $unit_user['id'])->orderBy('hierarchy', 'asc')->get()->toArray();
        if (count($childrens) > 0) {
            $j = 0;
            foreach ($childrens as $children) {
                $return['children'][$j] = CategoriesHelper::create_tree_checkbox($arr_sfaff_groups, $children);
                $j++;
            }
        }
        return $return;
    }

    /**
     * Tạo nhom table checkbox
     *
     * @param string $module : module của menu
     * @param string $menu : menu cha
     * @param boolean $active : là true là active trên form
     * @param string $parrenturl : url parent menu
     *
     * @return string html
     */
    public static function create_html_checkbox($groups) {
        $strHTML = '';
        $strHTML = $strHTML . '<table id="' . $sformFielName . '" class="griddata" width="100%" cellspacing="0" cellpadding="0">';
        $strHTML = $strHTML . '<colgroup><col width="5%"><col width="28%"><col width="5%"><col width="28%"><col width="5%"><col width="29%"></colgroup>';
        $strHTML = $strHTML . '<tr class="header">';
        $strHTML = $strHTML . "<td><input type='checkbox' name='checkall_process_per' onclick='checkallprovince(this)' /></td>";
        $strHTML = $strHTML . '<td colspan="5">Danh sách nhóm địa bàn quản lý</td>';
        $strHTML = $strHTML . '</tr>';
    }

    /**
     * Lay cac don vi con cua minh
     *
     * @return array
     */
    public static function get_tree_user_unit_by_id($unit_id, $Position, $user_name, $root) {
        $return = array();
        // lay cac don vi cap 1
        if ($unit_id !== '') {
            $units = UnitModel::where("id", $unit_id)->get(['id', 'name'])->toArray();
        } else {
            $root = $root + 1;
            $units = UnitModel::where("parent_id", NULL)->get(['id', 'name'])->toArray();
            $unit_id = $units[0]['id'];
        }
        if ($root == 1) {
            $return['icon'] = 'fas fa-folder-open folder-lv1';
        } elseif ($root == 2) {
            $return['icon'] = 'fas fa-folder-open folder-lv2';
        } elseif ($root == 3) {
            $return['icon'] = 'fas fa-folder-open folder-lv3';
        } elseif ($root == 4) {
            $return['icon'] = 'fas fa-folder-open folder-lv4';
        } elseif ($root == 5) {
            $return['icon'] = 'fas fa-folder-open folder-lv5';
        }
        // lay tat ca don vi con cua don vi day
        $child_units = UnitModel::where("parent_id", $unit_id)->orderBy('C_ORDER', 'asc')->get(['id', 'name'])->toArray();
        $return['id'] = $units[0]['id'];
        $return['text'] = "<span class='js-tree-text'>" . $units[0]['name'] . "</span>";
        $return['a_attr'] = array('type' => 'department');
        $return['state'] = array(
            "opened" => true
        );
        $i = 0;
        $root = $root + 1;
        if ($child_units) {
            // lay phong ban
            foreach ($child_units as $child_unit) {
                if ($child_unit) {
                    $return['children'][$i] = CategoriesHelper::create_tree_by_unit($child_unit, $Position, $user_name, $root);
                    $i++;
                }
            }
        }

        return $return;
    }

    public static function create_tree_by_unit($child_unit, $Position, $user_name, $root) {
        // kiem tra don vi co phong ban con khong
        $count = UnitModel::where("parent_id", $child_unit['id'])->count();
        $return['id'] = $child_unit['id'];
        $return['text'] = "<span class='js-tree-text'>" . $child_unit['name'] . "</span>";
        if ($root == 1) {
            $return['icon'] = 'fas fa-folder folder-lv1';
        } elseif ($root == 2) {
            $return['icon'] = 'fas fa-folder folder-lv2';
        } elseif ($root == 3) {
            $return['icon'] = 'fas fa-folder folder-lv3';
        } elseif ($root == 4) {
            $return['icon'] = 'fas fa-folder folder-lv4';
        } elseif ($root == 5) {
            $return['icon'] = 'fas fa-folder folder-lv5';
        }
        if ($count > 0) {
            $opened = true;
            $return['children'] = true;
        } else {
            $return['children'] = false;
        }
        return $return;
    }

    public static function get_user_by_child_unit($parent_unit, $search) {
        $query = ' WITH ret AS( ';
        $query .= ' SELECT  id ';
        $query .= ' FROM  chuyen_muc ';
        $query .= " WHERE   id = '" . $parent_unit . "' ";
        $query .= ' UNION ALL ';
        $query .= ' SELECT  t.id ';
        $query .= ' FROM    chuyen_muc t INNER JOIN ';
        $query .= ' ret r ON t.parent_id = r.id ';
        $query .= ' ) ';
        $query .= ' SELECT  PK_STAFF,B.name,B.C_STATUS,B.C_ROLE,C_USERNAME,B.C_ORDER,C.code as C_POSITION FROM ret A inner join T_USER_STAFF B on A.id = B.parent_id ';
        $query .= ' inner join T_USER_POSITION C on B.FK_POSITION = C.PK_POSITION ';
        $query .= " WHERE   id <> '" . $parent_unit . "' ";
        if ($search !== '') {
            $query .= " AND  (B.name like '%" . $search . "%' ";
            $query .= " OR  B.C_USERNAME like '%" . $search . "%') ";
        }
        return DB::select($query);
    }

    public static function get_unit_by_child_unit($parent_unit, $search) {
        $query = ' WITH ret AS( ';
        $query .= ' SELECT  id ';
        $query .= ' FROM  chuyen_muc ';
        $query .= " WHERE   id = '" . $parent_unit . "' ";
        $query .= ' UNION ALL ';
        $query .= ' SELECT  t.id ';
        $query .= ' FROM    chuyen_muc t INNER JOIN ';
        $query .= ' ret r ON t.parent_id = r.id ';
        $query .= ' ) ';
        $query .= ' SELECT  * from ret A inner join chuyen_muc B on A.id = B.id ';
        $query .= " WHERE A.id <> '" . $parent_unit . "' ";
        if ($search !== '') {
            $query .= " AND  (B.name like '%" . $search . "%' ";
            $query .= " OR  B.code like '%" . $search . "%') ";
        }
        return DB::select($query);
    }

    public static function get_root_by_id($id_unit,$ownercode) {
        // $query = ' WITH ret AS( ';
        // $query .= ' select id, parent_id ';
        // $query .= ' from chuyen_muc ';
        // $query .= " where id = '" . $id_unit . "' and owner_code = '".$ownercode."' ";
        // $query .= ' union all ';
        // $query .= ' select C.id, C.parent_id ';
        // $query .= ' from chuyen_muc c ';
        // $query .= " join ret p on C.id = P.parent_id  and owner_code = '".$ownercode."' ";
        // $query .= ' ) ';
        // $query .= ' SELECT  count(*) as level_root from ret ';
        // return DB::select($query);
        $query = "SELECT count(*) as level_root from chuyen_muc where id = '$id_unit' and owner_code = '$ownercode'";
        return DB::select($query);
    }

}
