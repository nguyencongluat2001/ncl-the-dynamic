<?php

namespace Modules\System\Cms\Helpers;

use Modules\System\Cms\Models\CategoriesModel;
use DB;

/**
 * Helper hỗ trợ module user.
 *
 * @author Toanph <skype: toanph1505>
 */
class TreeHelper {

    /**
     * Lay cac don vi con cua minh
     *
     * @return array
     */
    public static function zend_tree_categories($categories_id, $ownercode, $node_lv) {
        $_this = new self;
        $return = array();
        $column = ['id', 'name', 'status', 'slug', 'order', DB::raw('(SELECT name from system_list where code = chuyen_muc.layout ) as layout')];
        // lay cac don vi cap 1
        if ($categories_id !== '') {
            $categoriess = CategoriesModel::select($column)->where("id", $categories_id)->orderBy('order', 'asc')->first();
        } else {
            $node_lv = $node_lv + 1;
            $categoriess = CategoriesModel::select($column)->where("parent_id", NULL)->orderBy('order', 'asc')->first();
            $categories_id = $categoriess->id;
        }
        $return['icon'] = 'fas fa-university folder-lv';
        // lay pk don vi root
        $categoriesRoot = CategoriesModel::select('id')->whereNull('parent_id')->get()->toArray();
        $pkcategoriesRoot = $categoriesRoot[0]['id'];
        // lay tat ca don vi con cua don vi day
        $child_categoriess = CategoriesModel::where("parent_id", $categories_id)->orderBy('order', 'asc')->get($column)->toArray();
        if ($categories_id != $pkcategoriesRoot) {
            $child_categoriess = CategoriesModel::where("parent_id", $categories_id)->orderBy('order', 'asc')->get($column)->toArray();
        }
        $return['id'] = $categoriess->id;
        $return['node_lv'] = $node_lv;
        $return['name'] = $categoriess->name;
        $return['order'] = $categoriess->order;
        $return['slug'] = $categoriess->slug;
        $return['status'] = $categoriess->status;
        $return['layout'] = $categoriess->layout;
        $return['text'] = "<span class='js-tree-text'>" . $categoriess->name . "</span>";
        $return['state'] = array(
            "opened" => true
        );
        $i = 0;
        $node_lv = $node_lv + 1;
        if ($child_categoriess) {
            // lay phong ban
            foreach ($child_categoriess as $child_categories) {
                if ($child_categories) {
                    $return['children'][$i] = $_this->create_tree_by_categories($child_categories, $node_lv);
                    $i++;
                }
            }
        }
        return $return;
    }

    public static function create_tree_by_categories($child_categories, $node_lv) {
        // kiem tra don vi co phong ban con khong
        $count = CategoriesModel::where("parent_id", $child_categories['id'])->count();
        $return['id'] = $child_categories['id'];
        $return['node_lv'] = $node_lv;
        $return['name'] = $child_categories['name'];
        $return['order'] = $child_categories['order'];
        $return['status'] = $child_categories['status'];
        $return['layout'] = $child_categories['layout'];
        $return['slug'] = $child_categories['slug'];
        $return['text'] = "<span class='js-tree-text'>" . $child_categories['name'] . "</span>";
        $return['icon'] = 'fas fa-university folder-lv';

        if ($count > 0) {
            $opened = true;
            $return['children'] = true;
        } else {
            $return['children'] = false;
        }
        return $return;
    }

}
