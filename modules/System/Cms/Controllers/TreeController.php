<?php

namespace Modules\System\Cms\Controllers;

use App\Http\Controllers\Controller;
use Modules\Core\EFY\Library;
use Modules\System\Cms\Models\CategoriesModel;
use Modules\System\Cms\Helpers\TreeHelper;
use Modules\System\Cms\Helpers\CategoriesHelper;
use Illuminate\Http\Request;
use DB;
use Modules\Api\Services\Admin\UnitService;
use Modules\System\Cms\Services\TreeService;

/**
 * Lớp xử lý quản trị, phân quyền người sử dụng
 *
 * @author Toanph <skype: toanph155>
 */
class TreeController extends Controller {

    public function __construct(UnitService $unitService, TreeService $treeService)
    {
        $this->_unitService     = $unitService;
        $this->treeService = $treeService;
    }


    public function getCategories(Request $request)
    {
        $data = $this->treeService->getCategories($request->input());
		return \Response::JSON($data);
    }
    
    /**
     * Zend list user va categories
     * @param Request $request
     * @return jSon
     */
    public function zendlist(Request $request)
    {
        $objTreeHelper = new TreeHelper();
        $users = array();
        $idcategories = $request->idcategories;
        $node_lv = $request->node_lv;
        $text = $request->text;
        $type = 'user';
        if ($idcategories) {
            // lay don vi con
            $categoriess = categoriesModel::where("FK_categories", '=', $idcategories)->orderBy('C_ORDER', 'asc')->get(['PK_CATEGORIES', 'C_NAME', 'C_ADDRESS', 'C_STATUS', 'C_CODE'])->toArray();
            if ($categoriess) {
                $type = 'categories';
                $data = $categoriess;
            } else {
                $data = DB::table('USER_STAFF')
                    ->join('USER_POSITION', 'USER_STAFF.FK_POSITION', '=', 'USER_POSITION.PK_POSITION')
                    ->where("FK_categories", '=', $idcategories)
                    ->select(['PK_STAFF', 'USER_STAFF.C_NAME', 'C_ROLE', 'C_USERNAME', 'USER_STAFF.C_ORDER', 'C_ADDRESS', 'USER_STAFF.C_STATUS', 'USER_POSITION.C_CODE as C_POSITION'])
                    ->orderBy('USER_STAFF.C_ORDER', 'asc')
                    ->get()->toArray();
            }
        }
        $return['type'] = $type;
        $return['data'] = $data;
        return \Response::JSON($return);
    }

}
