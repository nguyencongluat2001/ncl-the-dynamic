<?php

namespace Modules\System\Cms\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Core\EFY\Library;
use Modules\System\Cms\Models\CitizendIdeaModel;
use Modules\System\Listtype\Models\ListModel;
use Modules\System\Cms\Models\DocumentsModel;
use Modules\Core\EFY\FileServer;
use DB;
use Uuid;

/**
 * Lớp xử lý quản trị, phân quyền người sử dụng
 *
 * @author Toanph <skype: toanph155>
 */
class CitizenIdeaController extends Controller {

    public function __construct() {
        $check = Library::checkPermissionController('CmsCitizenIdeaController');
        if (!$check) {
            die('Bạn không có quyền vào chức năng này');
        }
    }
    
    public function index() {
        $obj = new Library();
        $result = array();
        $result = $obj->_getAllFileJavaScriptCssArray('js', 'system/Cms/JS_CitizenIdea.js', ',', $result,'JS_CitizenIdea.min.js');
        $data['stringJsCss'] = json_encode($result);
        return view('Cms::citizen-idea.index', $data);
    }

    public function loadlist(Request $request) {
        $arrInput = $request->input();
        $search = isset($arrInput['search']) ? $arrInput['search'] : '';
        $objDocumentModel = new CitizendIdeaModel();
        $objResult = $objDocumentModel->_getAllBackend($arrInput['currentPage'], $arrInput['perPage'], $search);
        return \Response::JSON(array(
                    'Dataloadlist' => $objResult,
                    'pagination' => (string) $objResult->links('Cms::vendor.pagination.default'),
                    'perPage' => $arrInput['perPage'],
        ));
    }

    public function edit(Request $request) {
        $objLib = new Library();
        $data = [];
        $arrInput = $request->input();
        $objCitizenIdeaModel = new CitizendIdeaModel();
        $ListModel = new ListModel();
        $arrSingle = $objCitizenIdeaModel->where('PK_CMS_CITIZEN_IDEA', $arrInput['chk_item_id'])->get();
        $arrLinhVuc = $ListModel->_getAllbyCode('DM_LINH_VUC', false, ["C_CODE", "C_NAME"]);
        $data['arrSingle'] = $arrSingle[0];
        $data['arrLinhVuc'] = $arrLinhVuc;
        $data['objLib'] = $objLib;
        return view('Cms::Citizen-idea.edit', $data);
    }

    public function update(Request $request) {
        $arrInput = $request->input();
        $data = $arrInput['data'];
        $arrDataForm = $this->queryToArray($data);
        $status = isset($arrDataForm['C_STATUS']) ? 'DA_XEM' : 'MOI_GUI';
        if (isset($arrDataForm['PK_CMS_CITIZEN_IDEA']) && $arrDataForm['PK_CMS_CITIZEN_IDEA'] != '') {
            $id = $arrDataForm['PK_CMS_CITIZEN_IDEA'];
        } else {
            $id = Uuid::generate();
        }
        $citizenIdeaModel = CitizendIdeaModel::find($id);
        $citizenIdeaModel->C_STATUS = $status;
        $citizenIdeaModel->save();
        return array('success' => true, 'message' => 'Cập nhật thành công');
    }

    public function queryToArray($qry) {
        $result = array();
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

    public function getfile($idfile = '') {
        $objFileserver = new FileServer();
        $objFileserver->openfile($idfile);
    }

    public function delete(Request $request) {
        $arrInput = $request->input();
        $idlist = $arrInput['listitem'];
        $sql = "DELETE T_CMS_CITIZEN_IDEA WHERE CHARINDEX(CONVERT(varchar(50),PK_CMS_CITIZEN_IDEA),'$idlist') >0 ";
        DB::delete($sql);
        return array('success' => true, 'message' => 'Xóa thành công');
    }

}
