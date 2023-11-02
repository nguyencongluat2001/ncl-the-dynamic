<?php

namespace Modules\Core\Efy\FormDynamic\eForm;

use Modules\Core\Efy\FormDynamic\eForm\EformListtypeHelper;

class eFormConvert
{

	private $detaXmls;
	private $listypes = "";

	/**
	 * Generate array config Form
	 * 
	 * @param array $arrXmls Xml cấu hình đã convert sang array
	 * @return array
	 */
	public function GenerateForm($arrXmls)
	{
		$results = array();
		$return  = array();
		if (!isset($arrXmls['update_object'])) return $results;

		$descXmls       = $arrXmls['update_object']['table_struct_of_update_form'];
		$this->detaXmls = $arrXmls['update_object']['update_formfield_list'];
		if (!isset($descXmls["update_row_list"]["update_row"])) return $results;

		$descXmls = $descXmls["update_row_list"]["update_row"];
		$i        = 0;
		foreach ($descXmls as $descXml) {
			$rows = "";
			if (isset($descXml['tag_list']) && $descXml['tag_list']) {
				$rows = $descXml['tag_list'];
			}
			if ($rows !== "") {
				$results[$i]['rows'] = $this->generateRows($rows);
				$i++;
			} else {
				break;
			}
		}
		// Tach Danh muc
		$return['forms']     = $results;
		$return['listtypes'] = EformListtypeHelper::run($this->listypes);
		return $return;
	}

	/**
	 * Generate rows
	 * 
	 * @param string $row Tên các field trong 1 row
	 * @return array
	 */
	public function generateRows($row)
	{
		$return_rows = array();
		$arrColumns  = explode(',', $row);
		if (sizeof($arrColumns) > 0) {
			$return_rows['column'] = $this->generateCols($arrColumns);
		}

		return $return_rows;
	}

	/**
	 * Generate fields on row
	 * 
	 * @param array $cols
	 * @return array
	 */
	public function generateCols($cols)
	{
		$detaXmls    = $this->detaXmls;
		$return_cols = array();
		$i           = 0;
		foreach ($cols as $col) {
			if (isset($detaXmls[$col])) {
				$xmlCol       = $detaXmls[$col];
				$xmlCol['id'] = $col;
				if (!empty($xmlCol['listtype_code'])) {
					$listtype_code  = (string)$xmlCol['listtype_code'];
					$this->listypes = $this->listypes . "," . $listtype_code;
				}
				if (isset($xmlCol) && $xmlCol) {
					$return_cols[$i] = $xmlCol;
				}
				$i++;
			}
		}

		return $return_cols;
	}
}
