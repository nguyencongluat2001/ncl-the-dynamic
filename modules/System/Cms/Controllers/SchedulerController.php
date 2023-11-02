<?php

namespace Modules\System\Cms\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Core\EFY\Library;
use Modules\System\Cms\Models\SchedulerModel;
use Modules\System\Listtype\Models\ListModel;
use Modules\System\Users\Models\UnitModel;
use DB;
use Uuid;

/**
 * Lớp xử lý quản trị, nhóm người dùng
 *
 * @author Toanph <skype: toanph155>
 */
class SchedulerController extends Controller {

    public function __construct() {
        $check = Library::checkPermissionController('CmsSchedulerController');
        if (!$check) {
            die('Bạn không có quyền vào chức năng này');
        }
    }

    /**
     * khởi tạo dữ liệu mẫu, Load các file js, css của đối tượng
     *
     * @return view
     */
    public function index(Request $request) {
        $obj = new Library();
        $arrInput = $request->input();

        $result = array();
        $result = $obj->_getAllFileJavaScriptCssArray('js', 'system/Cms/JS_Scheduler.js', ',', $result, 'JS_Scheduler.min.js');
        $data['stringJsCss'] = json_encode($result);
        $objArticleModel = new SchedulerModel();
        if (isset($arrInput['C_WEEK'])) {
            $iWeek = $arrInput['C_WEEK'];
        } else {
            $iWeek = date("W");
        }
        if (isset($arrInput['C_YEAR'])) {
            $iYear = $arrInput['C_YEAR'];
        } else {
            $iYear = date('Y');
        }

        $arrySchedule_Unit = $objArticleModel->_getAll($iWeek, $iYear);
        $arryWekk = $obj->Generate_weeks_of_year($iYear, -1, $iWeek);
        $arrData = $this->convertArrScheduler($arrySchedule_Unit);
        $data['arrySchedule_Unit'] = $arrData;
        $data['arryWekk'] = $arryWekk;
        $data['iYear'] = $iYear;
        return view('Cms::Scheduler.index', $data);
    }

    public function scheduler_add() {
        $data = [];
        $listmodel = new ListModel();
        $ojbEfyLib = new Library();
        $objSchedulerModel = new SchedulerModel();
        $iYear = date('Y');
        $iWeek = date("W");
        $arryWekk = $ojbEfyLib->Generate_weeks_of_year($iYear, -1, $iWeek);
        $arrDayInWeek = $listmodel->_getAllbyCode('DM_NGAY_TRONG_TUAN', false, ['C_CODE', 'C_NAME']);
        $data['PK_SCHEDULE_UNIT'] = '';
        $data['arryWekk'] = $arryWekk;
        $data['arrDayInWeek'] = $arrDayInWeek;
        return view('Cms::Scheduler.add', $data);
    }

    public function scheduler_edit(Request $request) {
        $data = [];
        $arrInput = $request->input();
        $objLib = new Library();
        $listmodel = new ListModel();
        $objSchedulerModel = new SchedulerModel();
        $arrSingle = $objSchedulerModel->where('PK_SCHEDULE_UNIT', $arrInput['chk_item_id'])->get()->toArray();
        $arrDayInWeek = $listmodel->_getAllbyCode('DM_NGAY_TRONG_TUAN', false, ['C_CODE', 'C_NAME']);
        $data['arrSingle'] = $arrSingle[0];
        $iYear = $arrSingle[0]['C_YEAR'];
        $iWeek = $arrSingle[0]['C_WEEK'];
        $arryWekk = $objLib->Generate_weeks_of_year($iYear, -1, $iWeek);
        $data['PK_SCHEDULE_UNIT'] = $arrSingle[0]['PK_SCHEDULE_UNIT'];
        $data['arryWekk'] = $arryWekk;
        $data['objLib'] = $objLib;
        $data['arrDayInWeek'] = $arrDayInWeek;
        return view('Cms::Scheduler.add', $data);
    }

    public function scheduler_update(Request $request) {
        $arrInput = $request->input();
        $data = $arrInput['data'];
        $objLibrary = new Library();
        $arrDataForm = $this->queryToArray($data);
        $C_OPEN_NEW_WIN = isset($arrDataForm['C_OPEN_NEW_WIN']) ? 1 : 0;
        $C_STATUS = isset($arrDataForm['C_STATUS']) ? 1 : 0;
        if (isset($arrDataForm['PK_SCHEDULE_UNIT']) && $arrDataForm['PK_SCHEDULE_UNIT'] != '') {
            $id = $arrDataForm['PK_SCHEDULE_UNIT'];
        } else {
            $id = Uuid::generate();
        }
        $schedulerModel = SchedulerModel::find($id);
        if (!isset($schedulerModel)) {
            $schedulerModel = new SchedulerModel();
        }
        if ($_FILES) {
            $basepath = base_path('public\cms_attach_file\\');
            $year = date('Y');
            $month = date('m');
            $date = date('d');
            $folder = $objLibrary->_createFolder($basepath, $year, $month, $date);
            $fileattach = '';
            foreach ($_FILES as $key => $value) {
                $filename = $year . '_' . $month . '_' . $date . '_' . time() . '!~!' . $value['name'];
                $filename = str_replace(' ', '_', $filename);
                copy($value['tmp_name'], $folder . $filename);
                $fileattach .= ',' . $filename;
            }
            $schedulerModel->C_VIDEO_FILE_NAME = trim($fileattach, ',');
        }
        $schedulerModel->PK_SCHEDULE_UNIT = $id;
        $schedulerModel->FK_CREATE_STAFF = $_SESSION['user_infor']['PK_STAFF'];
        $schedulerModel->C_NAME_JOINER = $arrDataForm['C_NAME_JOINER'];
        $schedulerModel->C_WEEK = $arrDataForm['C_WEEK'];
        $schedulerModel->C_YEAR = $arrDataForm['C_YEAR'];
        $schedulerModel->C_DAY = $arrDataForm['C_DAY'];
        $schedulerModel->C_DAY_PART = $arrDataForm['C_DAY_PART'];
        $schedulerModel->C_START_TIME = $arrDataForm['C_START_TIME'];
        $schedulerModel->C_FINISH_TIME = $arrDataForm['C_FINISH_TIME'];
        $schedulerModel->C_WORK_NAME = $arrDataForm['C_WORK_NAME'];
        $schedulerModel->C_PLACE = $arrDataForm['C_PLACE'];
        $schedulerModel->C_PREPARE_ORGAN = $arrDataForm['C_PREPARE_ORGAN'];
        $schedulerModel->C_ATTENDING = $arrDataForm['C_ATTENDING'];
        $schedulerModel->C_OWNER_CODE = $_SESSION['OWNER_CODE'];
        $schedulerModel->C_STATUS = $C_STATUS;
        $schedulerModel->save();
        return array('success' => true, 'message' => 'Cập nhật thành công', 'parent_id' => $request->parent_id);
    }

    public function scheduler_delete(Request $request) {
        $arrInput = $request->input();
        $idlist = $arrInput['listitem'];
        $sql = "DELETE T_SCHEDULE_UNIT WHERE CHARINDEX(CONVERT(varchar(50),PK_SCHEDULE_UNIT),'$idlist') >0 ";
        DB::delete($sql);
        return array('success' => true, 'message' => 'Cập nhật thành công', 'parent_id' => $request->parent_id);
    }

    public function queryToArray($qry) {
        $result = array();
//string must contain at least one = and cannot be in first position
        if (strpos($qry, '=')) {
            if (strpos($qry, '?') !== false) {
                $q = parse_url($qry);
                $qry = $q['query'];
            }
        } else {
            return false;
        }

        foreach (explode('&', $qry) as $couple) {
            list($key, $val) = explode('=', $couple);
            if (isset($result[$key])) {
                $result[$key] = trim($result[$key] . ';' . urldecode($val), ';');
            } else {
                $result[$key] = urldecode($val);
            }
        }

        return empty($result) ? false : $result;
    }

    public function convertArrScheduler($arrScheduler) {
        $arrReturn = array();
        foreach ($arrScheduler as $key => $value) {
            if (isset($arrReturn[$value['C_DAY']])) {
                array_push($arrReturn[$value['C_DAY']], $value);
            } else {
                $arrReturn[$value['C_DAY']][0] = $value;
            }
        }
        return $arrReturn;
    }

}
