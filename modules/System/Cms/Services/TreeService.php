<?php

namespace Modules\System\Cms\Services;

use Modules\Core\Ncl\Http\BaseService;
use Modules\System\Cms\Helpers\CategoriesHelper;
use Modules\System\Cms\Helpers\TreeHelper;

class TreeService
{
    public function __construct(CategoriesService $categoriesService)
    {
        $this->categoriesService = $categoriesService;
    }
    public function getCategories($data)
    {
		$category_id = $root= '';
        if(isset($data['id']) && $data['id'] !== '' && $data['id'] !== '#'){
			$category_id = $data['id'];
		}else{
            $categoriess = $this->categoriesService->where("parent_id", null)->orderBy('order', 'asc')->first();
            // if ($_SESSION["role"] == 'ADMIN_SYSTEM') {
            //     $category_id = $categoriess->id;
            // } else {
                $category_id = $categoriess->id ?? null;
            // }
        }
        $owner_code = $_SESSION['OWNER_CODE'] ?? null;
        // lay root cua don vi
        $root = CategoriesHelper::get_root_by_id($category_id, $owner_code);
        // du lieu tra ve dang json
        $result = TreeHelper::zend_tree_categories($category_id, $owner_code, $root[0]->level_root);
        return $result;
    }
}