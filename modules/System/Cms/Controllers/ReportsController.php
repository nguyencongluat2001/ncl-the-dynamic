<?php

namespace Modules\System\Cms\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Core\Ncl\Library;
use Modules\System\Cms\Models\ArticlesModel;
use Modules\System\Cms\Models\CategoriesModel;
use Modules\System\Listtype\Models\ListModel;
use URL;
use DB;
use Uuid;

/**
 * Lớp xử lý quản trị, nhóm người dùng
 *
 * @author Toanph <skype: toanph155>
 */
class ReportsController extends Controller {

    public function __construct() {
        $check = Library::checkPermissionController('CmsReportsController');
        if (!$check) {
            die('Bạn không có quyền vào chức năng này');
        }
    }

    public function index() {
        $obj = new Library();
        $ListModel = new ListModel();
        $result = array();
        $result = $obj->_getAllFileJavaScriptCssArray('js', 'system/Cms/JS_Reports.js', ',', $result, 'JS_Reports.min.js');
        $data['stringJsCss'] = json_encode($result);
        $sql = "SELECT PK_CATEGORIES,C_NAME FROM T_CMS_CATEGORIES WHERE DBO.F_CHECKLASTCATEGORY(PK_CATEGORIES) = 1 AND  C_OWNER_CODE = '" . $_SESSION['OWNER_CODE'] . "' order by C_ORDER";
        $arrCategory = DB::select($sql);
        $data['arrCategory'] = $arrCategory;
        $arrLoaiTinBai = $ListModel->_getAllbyCode('DM_LOAI_TIN_BAI', false, ["C_CODE", "C_NAME"]);
        $data['arrLoaiTinBai'] = $arrLoaiTinBai;
        return view('Cms::Reports.index', $data);
    }

    public function loadlist(Request $request) {
        $arrInput = $request->input();
        $objLib = new Library();
        $fromdate = $objLib->_ddmmyyyyToYYyymmdd($arrInput['fromdate']);
        $todate = $objLib->_ddmmyyyyToYYyymmdd($arrInput['todate']);
        $category = $arrInput['category'];
        $search = $arrInput['search'];
        $objArticleModel = new ArticlesModel();
        $objResult = $objArticleModel->_getAll($arrInput['currentPage'], $arrInput['perPage'], $search, $fromdate, $todate, $category, '');
        return \Response::JSON(array(
                    'Dataloadlist' => $objResult,
                    'pagination' => (string) $objResult->links('Cms::vendor.pagination.default'),
                    'perPage' => $arrInput['perPage'],
        ));
    }

    public function export_excel(Request $request) {
        $arrInput = $request->input();

        $objLib = new Library();
        $fromdate = $objLib->_ddmmyyyyToYYyymmdd($arrInput['fromdate']);
        $todate = $objLib->_ddmmyyyyToYYyymmdd($arrInput['todate']);
        $category = $arrInput['category'];
        $objArticleModel = new ArticlesModel();
        $datas = $objArticleModel->_getAllExport($fromdate, $todate, $category, '');
        $objPHPExcel = \PHPExcel_IOFactory::load(base_path() . "/public/template/Export_Reports.xlsx");
        $objPHPExcel->getActiveSheet()
                ->getStyle('A1:AG100')
                ->getAlignment()
                ->setWrapText(true);
        $BStyle = array(
            'borders' => array(
                'allborders' => array(
                    'style' => \PHPExcel_Style_Border::BORDER_THIN
                )
            ),
        );
        $boldStyle = array(
            'name' => 'Times New Roman',
            'italic' => false,
            'strike' => false,
            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array(
                'rgb' => 'F28A8C'
            ),
            'alignment' => array(
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );
        $objWorksheet_template = $objPHPExcel->getActiveSheet();
        // $objWorksheet_template->setTitle('Báo cáo so sánh');
        $provinceSheet = $objPHPExcel->setActiveSheetIndex(0);
        $objWorksheet_template->getStyleByColumnAndRow(0, 4, 5, 4 + sizeof($datas))->applyFromArray($BStyle);
        $i = 4;
        $j = 1;
        if ($arrInput['fromdate'] != "" && $arrInput['todate'] != "") {
            $dong2 = 'Từ ngày ' . $arrInput['fromdate'] . ' đến ngày ' . $arrInput['todate'];
        } else {
            $dong2 = "";
        }
        $provinceSheet->setCellValueByColumnAndRow(0, 2, $dong2);
        foreach ($datas as $data) {
            $provinceSheet->setCellValueByColumnAndRow(0, $i, $j);
            $provinceSheet->setCellValueByColumnAndRow(1, $i, $data->C_CREATE);
            $provinceSheet->setCellValueByColumnAndRow(2, $i, $data->C_TITLE);
            $provinceSheet->setCellValueByColumnAndRow(3, $i, $data->CATEGORY_NAME);
            $provinceSheet->setCellValueByColumnAndRow(4, $i, $data->C_AUTHOR);
            $provinceSheet->setCellValueByColumnAndRow(5, $i, $data->C_CREATE_STAFF_NAME);
            $i++;
            $j++;
        }
        $objPHPExcel->getActiveSheet()->removeRow($i);
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(base_path() . "/public/export/bao_cao.xls");
        return \Response::JSON(array('success' => true, 'message' => 'Xuất excel thành công', 'urlfile' => url('/public/export/bao_cao.xls')));
    }

    public function detail() {
        $sql = "EXEC CMS_TOTAL_ARTICLE";
        $total = DB::select($sql);
        $data['total'] = $total;
        return view('Cms::Reports.detail', $data);
    }

}
