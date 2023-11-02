<?php

namespace Modules\System\Cms\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Core\EFY\Library;
use Modules\System\Cms\Models\VideosModel;
use Modules\System\Listtype\Models\ListModel;
use Modules\System\Users\Models\UnitModel;
use DB;
use Uuid;

/**
 * Lớp xử lý quản trị, nhóm người dùng
 *
 * @author Toanph <skype: toanph155>
 */
class VideosController extends Controller {

    public function __construct() {
        $check = Library::checkPermissionController('CmsVideosController');
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
        $result = array();
        $result = $obj->_getAllFileJavaScriptCssArray('js', 'system/Cms/JS_Videos.js', ',', $result,'JS_Videos.min.js');
        $data['stringJsCss'] = json_encode($result);
        $arrData = VideosModel::all()->toArray();
        $data['data'] = $arrData;
        return view('Cms::Videos.index', $data);
    }

    public function loadlist(Request $request) {
        $objLib = new Library();
        $arrInput = $request->input();
        $objArticleModel = new VideosModel();
        $objResult = $objArticleModel->_getAll($arrInput['currentPage'], $arrInput['perPage'], $arrInput['search']);
        $arrData = $objResult->toArray();
        for ($i = 0; $i < sizeof($arrData['data']); $i++) {
            if ($arrData['data'][$i]['C_SOURCE_VIDEO'] == 'UPLOAD') {
                $arrData['data'][$i]['C_VIDEO_FILE_NAME'] = url('public/cms_attach_file') . '/' . $objLib->_getfolderbyfilename($arrData['data'][$i]['C_VIDEO_FILE_NAME']);
            } else {
                $arrData['data'][$i]['C_VIDEO_FILE_NAME'] = $arrData['data'][$i]['C_URL_VIDEO'];
            }
        }
        return \Response::JSON(array(
                    'Dataloadlist' => $arrData,
                    'pagination' => (string) $objResult->links('Cms::vendor.pagination.default'),
                    'perPage' => $arrInput['perPage'],
        ));
    }

    public function videos_add() {
        $data = [];
        $listmodel = new ListModel();
        $objVideosModel = new VideosModel();
        $data['C_ORDER'] = $objVideosModel->count('*') + 1;
        $arrVitriAnh = $listmodel->_getAllbyCode('DM_VI_TRI_ANH', false, ['C_CODE', 'C_NAME']);
        $data['arrVitriAnh'] = $arrVitriAnh;
        $data['PK_CMS_VIDEOS'] = '';
        $data['C_STATUS']=1;
        return view('Cms::Videos.add', $data);
    }

    public function videos_edit(Request $request) {
        $data = [];
        $arrInput = $request->input();
        $objLib = new Library();
        $listmodel = new ListModel();
        $objVideosModel = new VideosModel();
        $arrSingle = $objVideosModel->where('PK_CMS_VIDEOS', $arrInput['chk_item_id'])->get()->toArray();
        $arrVitriAnh = $listmodel->_getAllbyCode('DM_VI_TRI_ANH', false, ['C_CODE', 'C_NAME']);
        $data['arrVitriAnh'] = $arrVitriAnh;
        if ($arrSingle[0]['C_SOURCE_VIDEO'] == 'UPLOAD') {
            $arrSingle[0]['C_VIDEO_FILE_NAME'] = url('public/cms_attach_file') . '/' . $objLib->_getfolderbyfilename($arrSingle[0]['C_VIDEO_FILE_NAME']);
        } else {
            $arrSingle[0]['C_VIDEO_FILE_NAME'] = $arrSingle[0]['C_URL_VIDEO'];
        }
        $data['arrSingle'] = $arrSingle[0];
        $data['PK_CMS_VIDEOS'] = $arrSingle[0]['PK_CMS_VIDEOS'];
        $data['C_ORDER'] = $arrSingle[0]['C_ORDER'];
        $data['objLib'] = $objLib;
        $data['C_STATUS']=$arrSingle[0]['C_STATUS'];
        return view('Cms::Videos.add', $data);
    }

    public function videos_update(Request $request) {
        $arrInput = $request->input();
        $data = $arrInput['data'];
        $objLibrary = new Library();
        $arrDataForm = $this->queryToArray($data);
        $C_OPEN_NEW_WIN = isset($arrDataForm['C_OPEN_NEW_WIN']) ? 1 : 0;
        $C_STATUS = isset($arrDataForm['C_STATUS']) ? 1 : 0;
        if (isset($arrDataForm['PK_CMS_VIDEOS']) && $arrDataForm['PK_CMS_VIDEOS'] != '') {
            $id = $arrDataForm['PK_CMS_VIDEOS'];
        } else {
            $id = Uuid::generate();
        }
        $videosModel = VideosModel::find($id);
        if (!isset($videosModel)) {
            $videosModel = new VideosModel();
            $videosModel->C_INPUT_DATE = date('Y/m/d H:i:s');
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
            $videosModel->C_VIDEO_FILE_NAME = trim($fileattach, ',');
        }
        $videosModel->PK_CMS_VIDEOS = $id;
        $videosModel->C_NAME = $arrDataForm['C_NAME'];
        $videosModel->C_URL = $arrDataForm['C_URL'];
        $videosModel->C_SOURCE_VIDEO = $arrDataForm['C_SOURCE_VIDEO'];
        $videosModel->C_URL_VIDEO = isset($arrDataForm['C_URL_VIDEO']) ? $arrDataForm['C_URL_VIDEO'] : '';
        $videosModel->C_ORDER = $arrDataForm['C_ORDER'];
        $videosModel->C_OWNER_CODE = $_SESSION['OWNER_CODE'];
        $videosModel->C_BEGIN_DATE = $objLibrary->_ddmmyyyyToYYyymmdd($arrDataForm['C_BEGIN_DATE']);
        $videosModel->C_END_DATE = $objLibrary->_ddmmyyyyToYYyymmdd($arrDataForm['C_END_DATE']);
        $videosModel->C_STATUS = $C_STATUS;
        $videosModel->save();
        return array('success' => true, 'message' => 'Cập nhật thành công', 'parent_id' => $request->parent_id);
    }

    public function videos_delete(Request $request) {
        $arrInput = $request->input();
        $idlist = $arrInput['listitem'];
        $sql = "DELETE T_CMS_VIDEOS WHERE CHARINDEX(CONVERT(varchar(50),PK_CMS_VIDEOS),'$idlist') >0 ";
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

    public function convertarticles() {
        $sql = "select * from T_CMS_ARTICLES";
        $arrArticles = DB::select($sql);
        foreach ($arrArticles as $key => $value) {
            $C_SUBJECT = html_entity_decode(html_entity_decode($value->C_SUBJECT));
            $C_SUBJECT = strip_tags($C_SUBJECT);
            $C_FEATURE_IMG = '';
            if ($value->C_FEATURE_IMG != '') {
                $C_FEATURE_IMG = str_replace("/UploadFile/Portals/0/AttachFiles/", '', $value->C_FEATURE_IMG);
                $arrIMG = explode('/', $C_FEATURE_IMG);
                $arrS = array_slice($arrIMG, 3);
                $C_FEATURE_IMG = implode('/', $arrS);
                $arrXX = explode('_', $C_FEATURE_IMG);
                $arrXX[3] = '!~!' . $arrXX[3];
                $C_FEATURE_IMG = implode('_', $arrXX);
            }
            $C_CONTENT = html_entity_decode(html_entity_decode($value->C_CONTENT));
            $sql = "UPDATE T_CMS_ARTICLES  set C_FEATURE_IMG = '$C_FEATURE_IMG',C_SUBJECT = N'$C_SUBJECT',C_CONTENT = N'$C_CONTENT' Where PK_CMS_ARTICLE = '" . $value->PK_CMS_ARTICLE . "'";
            DB::update($sql);
        }
        dd($arrArticles);
    }

}
