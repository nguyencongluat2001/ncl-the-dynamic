<?php

namespace Modules\System\Users\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\System\Users\Services\TreeService;

/**
 * Lớp xử lý quản trị, phân quyền người sử dụng
 *
 * @author Toanph <skype: toanph155>
 */
class TreeController extends Controller
{
	private $treeService;

	public function __construct(TreeService $t) {
		$this->treeService = $t;
	}

	/**
	 * Lay case thu muc doi tuong cap 1
	 * 
	 * @param Request $request
	 * @return Response
	 */
	public function getUnit(Request $request)
	{
		$units = $this->treeService->getUnit($request->input());
		return response()->json($units);
	}

	/**
	 * Zend list user va unit
	 * 
	 * @param Request $request
	 * @return jSon
	 */
	public function zendlist(Request $request)
	{
		$result = $this->treeService->zendList($request->input());
		return response()->json($result);
	}
}
