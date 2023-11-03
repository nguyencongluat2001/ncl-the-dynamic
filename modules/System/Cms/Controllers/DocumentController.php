<?php

namespace Modules\System\Cms\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Core\Ncl\Library;
use Modules\System\Cms\Models\DocumentsModel;
use Modules\System\Listtype\Models\ListModel;
use URL;
use DB;
use Uuid;

/**
 * Lớp xử lý quản trị, nhóm người dùng
 *
 * @author Toanph <skype: toanph155>
 */
class DocumentController extends Controller {

    public function __construct() {
        $check = Library::checkPermissionController('CmsDocumentController');
        if (!$check) {
            die('Bạn không có quyền vào chức năng này');
        }
    }

    /**
     * khởi tạo dữ liệu mẫu, Load các file js, css của đối tượng
     *
     * @return view
     */
    public function index() {
        $obj = new Library();
        $ListModel = new ListModel();
        $result = array();
        $result = $obj->_getAllFileJavaScriptCssArray('js', 'system/Cms/JS_Documents.js', ',', $result,'JS_Documents.min.js');
        $data['stringJsCss'] = json_encode($result);
        $arrLoaiVanBan = $ListModel->_getAllbyCode('DM_LOAI_VAN_BAN', false, ["C_CODE", "C_NAME"]);
        $data['arrLoaiVanBan'] = $arrLoaiVanBan;
        return view('Cms::Documents.index', $data);
    }

    public function loadlist(Request $request) {
        $arrInput = $request->input();
        $objLib = new Library();
        $fromdate = $objLib->_ddmmyyyyToYYyymmdd($arrInput['fromdate']);
        $todate = $objLib->_ddmmyyyyToYYyymmdd($arrInput['todate']);
        $search = $arrInput['search'];
        $documents_type = $arrInput['documents_type'];
        $dateeffect = $objLib->_ddmmyyyyToYYyymmdd($arrInput['dateeffect']);
        $objDocumentModel = new DocumentsModel();
        $objResult = $objDocumentModel->_getAll($arrInput['currentPage'], $arrInput['perPage'], $search, $fromdate, $todate, $documents_type, $dateeffect);
        return \Response::JSON(array(
                    'Dataloadlist' => $objResult,
                    'pagination' => (string) $objResult->links('Cms::vendor.pagination.default'),
                    'perPage' => $arrInput['perPage'],
        ));
    }

    public function documents_add() {
        $data = [];
        $ListModel = new ListModel();
        $arrLoaiVanBan = $ListModel->_getAllbyCode('DM_LOAI_VAN_BAN', false, ["C_CODE", "C_NAME"]);
        $data['arrLoaiVanBan'] = $arrLoaiVanBan;
        $DocumentsModel = new DocumentsModel();
        $data['C_ORDER'] = $DocumentsModel->count('*') + 1;
        return view('Cms::Documents.add', $data);
    }

    public function documents_edit(Request $request) {
        $data = [];
        $arrInput = $request->input();
        $objLib = new Library();
        $objDocumentsModel = new DocumentsModel();
        $ListModel = new ListModel();
        $arrSingle = $objDocumentsModel->where('PK_CMS_DOCUMENT', $arrInput['chk_item_id'])->get();
        $arrLoaiVanBan = $ListModel->_getAllbyCode('DM_LOAI_VAN_BAN', false, ["C_CODE", "C_NAME"]);
        $arrSingle[0]->C_CREATE_DATE = $objLib->_yyyymmddToDDmmyyyy($arrSingle[0]->C_CREATE_DATE, true);
        $arrSingle[0]->C_DATE_PUBLIC = $objLib->_yyyymmddToDDmmyyyy($arrSingle[0]->C_DATE_PUBLIC, true);
        $arrSingle[0]->C_DATE_EFFECT = $objLib->_yyyymmddToDDmmyyyy($arrSingle[0]->C_DATE_EFFECT, true);
        $data['arrSingle'] = $arrSingle[0];
        $data['arrLoaiVanBan'] = $arrLoaiVanBan;
        $data['objLib'] = $objLib;
        $data['C_ORDER'] = $arrSingle[0]['C_ORDER'];
        $data['C_IS_DECISION_PROCEDURE'] = $arrSingle[0]['C_IS_DECISION_PROCEDURE'];
        return view('Cms::Documents.add', $data);
    }

    public function update(Request $request) {
        $arrInput = $request->input();
        $data = $arrInput['data'];
        $objLibrary = new Library();
        $arrDataForm = $this->queryToArray($data);
        $status = isset($arrDataForm['C_STATUS']) ? 'HOAT_DONG' : 'KHONG_HOAT_DONG';
        $id = $arrDataForm['PK_CMS_DOCUMENT'];
        $documentsModel = DocumentsModel::find($id);
        if (!isset($documentsModel)) {
            $documentsModel = new DocumentsModel();
            $documentsModel->C_CREATE_DATE = $objLibrary->_ddmmyyyyToYYyymmdd(date('d/m/Y'));
        }
        $fileattach = '';
        if ($_FILES) {
            $basepath = base_path('public\cms_attach_file\\');
            $year = date('Y');
            $month = date('m');
            $date = date('d');
            $folder = $objLibrary->_createFolder($basepath, $year, $month, $date);
            foreach ($_FILES as $key => $value) {
                if ($key != 'C_FEATURE_IMG') {
                    $filename = $year . '_' . $month . '_' . $date . '_' . time() . '!~!' . $value['name'];
                    $filename = str_replace(' ', '_', $filename);
                    copy($value['tmp_name'], $folder . $filename);
                    $fileattach .= ',' . $filename;
                }
            }
            $documentsModel->C_FILE_NAME = trim($documentsModel->C_FILE_NAME .= trim($fileattach, ','));
        }
        $C_IS_DECISION_PROCEDURE = '0';
        if (isset($arrDataForm['C_IS_DECISION_PROCEDURE']) && $arrDataForm['C_DOCTYPE'] == 'QD') {
            $C_IS_DECISION_PROCEDURE = '1';
        }
        $documentsModel->C_OWNER_CODE = $_SESSION['OWNER_CODE'];
        $documentsModel->C_SYMBOL = $arrDataForm['C_SYMBOL'];
        $documentsModel->C_CREATE_STAFF_NAME = $_SESSION['user_infor']['C_NAME'];
        $documentsModel->C_SUBJECT = $arrDataForm['C_SUBJECT'];
        $documentsModel->C_DOCTYPE = $arrDataForm['C_DOCTYPE'];
        $documentsModel->C_SIGNER = $arrDataForm['C_SIGNER'];
        $documentsModel->C_UNIT_PUBLIC = $arrDataForm['C_UNIT_PUBLIC'];
        $documentsModel->C_DATE_PUBLIC = $objLibrary->_ddmmyyyyToYYyymmdd($arrDataForm['C_DATE_PUBLIC']);
        $documentsModel->C_DATE_EFFECT = $objLibrary->_ddmmyyyyToYYyymmdd($arrDataForm['C_DATE_EFFECT']);
        $documentsModel->C_ORDER = 1;
        $documentsModel->C_STATUS = $status;
        $documentsModel->C_IS_DECISION_PROCEDURE = $C_IS_DECISION_PROCEDURE;
        $documentsModel->save();
        return array('success' => true, 'message' => 'Cập nhật thành công', 'parent_id' => $request->parent_id);
    }

    public function documents_delete(Request $request) {
        $arrInput = $request->input();
        $idlist = $arrInput['listitem'];
        $sql = "DELETE T_CMS_DOCUMENT WHERE CHARINDEX(CONVERT(varchar(50),PK_CMS_DOCUMENT),'$idlist') >0 ";
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

    public function renamefile() {
        $path = base_path('public\cms_attach_file\2019\12\31');
        $arr_file = scandir($path);
        foreach ($arr_file as $key => $value) {
            if ($key > 1) {
                $oldname = $path.'\\'.$value;
                $newname = $path.'\\'.'2019_12_31_1586401856!~!'.$value;
                rename($oldname, $newname);
                echo  $value . '<br>';
            }
        }
    }

    public function deletefile(Request $request) {
        $arrInput = $request->input();
        $filename = $arrInput['filename'];
        $pkrecord = $arrInput['pkrecord'];
        $arrData['filename'] = $filename;
        $arrData['pkrecord'] = $pkrecord;
        $sql = "SELECT * FROM T_CMS_DOCUMENT WHERE PK_CMS_DOCUMENT = '$pkrecord'";
        $document = DB::select($sql);
        $document = array_map(function($value) {
            return (array) $value;
        }, $document);
        $explodeStr = explode(',', $document[0]['C_FILE_NAME']);
        foreach ($explodeStr as $key => $value) {
            if ($filename == $value) {
                unset($explodeStr[$key]);
            }
        }
        $file = implode(',', $explodeStr);
        DB::update("UPDATE T_CMS_DOCUMENT SET C_FILE_NAME = '$file' WHERE PK_CMS_DOCUMENT = '$pkrecord'" );
        return \Response::JSON(array(
            'FileData' => $arrData,
        ));
    }

}
