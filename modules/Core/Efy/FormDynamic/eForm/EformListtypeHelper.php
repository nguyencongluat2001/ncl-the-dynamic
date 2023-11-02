<?php

namespace Modules\Core\Efy\FormDynamic\eForm;

use Modules\System\Listtype\Helpers\ListtypeHelper;
use Modules\System\Listtype\Models\ListNationalModel;
use Modules\System\Position\Models\PositionModel;

/**
 * Helper xử lý liên quan đến danh mục
 *
 * @author Toanph <skype: toanph1505>
 */
class EformListtypeHelper
{

	/**
	 * Lấy các danh mục
	 * 
	 * @param string $listtypes Các mã danh mục
	 * @return array
	 */
	public static function run($listtypes)
	{
		$data         = array();
		$listtypes    = trim($listtypes, ",");
		$arrListtypes = explode(",", $listtypes);
		$i            = 0;
		foreach ($arrListtypes as $listtype) {
			if ($listtype) {
				$data[$i] = self::getListtype($listtype);
				$i++;
			}
		}

		return $data;
	}

	/**
	 * Lấy danh mục
	 * 
	 * @param string $listtype Mã danh mục
	 * @return array
	 */
	public static function getListtype($listtype)
	{
		$returns        = array();
		$options        = array();
		switch ($listtype) {
			case 'OTHER_CHUC_VU':
				$options = self::getChucVu();
				break;
			default:
				$options = self::getList($listtype);
				break;
		}
		$returns['code']    = $listtype;
		$returns['options'] = $options;

		return $returns;
	}

	/**
	 * Lấy danh mục
	 * 
	 * @param string $listtype mã danh mục
	 * @return array
	 */
	public static function getList($listtype)
	{
		$conf = config('listNational.onegate_to_national');
		if (array_key_exists($listtype, $conf)) {
			return (new ListNationalModel())->_getAllBySystemType($conf[$listtype]);
		} else {
			return (new ListtypeHelper())->_GetAllListObjectByListCode($listtype);
		}
	}

	/**
	 * Lấy chức vụ
	 */
	public static function getChucVu()
	{
		$PositionGroupModel = new PositionModel;
		$objResult          = $PositionGroupModel->_getAll(1, 1000, "");

		return $objResult;
	}
}
