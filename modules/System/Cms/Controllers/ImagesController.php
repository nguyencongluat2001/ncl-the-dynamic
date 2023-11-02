<?php

namespace Modules\System\Cms\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Core\EFY\Library;
use Modules\System\Cms\Models\ImagesModel;
use Modules\System\Listtype\Models\ListModel;
use Modules\System\Users\Models\UnitModel;
use DB;
use Uuid;

/**
 * Lớp xử lý quản trị, nhóm người dùng
 *
 * @author Toanph <skype: toanph155>
 */
class ImagesController extends Controller {

    public function __construct() {
        $check = Library::checkPermissionController('CmsImagesController');
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
        $result = $obj->_getAllFileJavaScriptCssArray('js', 'system/Cms/JS_Images.js', ',', $result,'JS_Images.min.js');
        $data['stringJsCss'] = json_encode($result);
        $arrData = ImagesModel::all()->toArray();
        $data['data'] = $arrData;
        return view('Cms::Images.index', $data);
    }

    public function loadlist(Request $request) {
        $objLib = new Library();
        $arrInput = $request->input();
        $objArticleModel = new ImagesModel();
        $objResult = $objArticleModel->_getAll($arrInput['currentPage'], $arrInput['perPage'], $arrInput['search']);
        $arrData = $objResult->toArray();
        for ($i = 0; $i < sizeof($arrData['data']); $i++) {
            $arrData['data'][$i]['C_IMAGE_FILE_NAME'] = url('public/cms_attach_file') . '/' . $objLib->_getfolderbyfilename($arrData['data'][$i]['C_IMAGE_FILE_NAME']);
        }
        return \Response::JSON(array(
                    'Dataloadlist' => $arrData,
                    'pagination' => (string) $objResult->links('Cms::vendor.pagination.default'),
                    'perPage' => $arrInput['perPage'],
        ));
    }

    public function images_add() {
        $data = [];
        $listmodel = new ListModel();
        $objImagesModel = new ImagesModel();
        $data['C_ORDER'] = $objImagesModel->count('*') + 1;
        $arrVitriAnh = $listmodel->_getAllbyCode('DM_VI_TRI_ANH', false, ['C_CODE', 'C_NAME']);
        $data['arrVitriAnh'] = $arrVitriAnh;
        $data['PK_CMS_IMAGEADVERTISE'] = '';
        $data['C_STATUS']=1;
        return view('Cms::Images.add', $data);
    }

    public function images_edit(Request $request) {
        $data = [];
        $arrInput = $request->input();
        $objLib = new Library();
        $listmodel = new ListModel();
        $objImagesModel = new ImagesModel();
        $arrSingle = $objImagesModel->where('PK_CMS_IMAGEADVERTISE', $arrInput['chk_item_id'])->get()->toArray();
        $arrVitriAnh = $listmodel->_getAllbyCode('DM_VI_TRI_ANH', false, ['C_CODE', 'C_NAME']);
        $data['arrVitriAnh'] = $arrVitriAnh;
        if ($arrSingle[0]['C_IMAGE_FILE_NAME'] != '')
            $arrSingle[0]['C_IMAGE_FILE_NAME'] = url('public/cms_attach_file') . '/' . $objLib->_getfolderbyfilename($arrSingle[0]['C_IMAGE_FILE_NAME']);
        $data['arrSingle'] = $arrSingle[0];
        $data['PK_CMS_IMAGEADVERTISE'] = $arrSingle[0]['PK_CMS_IMAGEADVERTISE'];
        $data['C_ORDER'] = $arrSingle[0]['C_ORDER'];
        $data['objLib'] = $objLib;
        $data['C_STATUS']=$arrSingle[0]['C_STATUS'];
        return view('Cms::Images.add', $data);
    }

    public function images_update(Request $request) {
        $arrInput = $request->input();
        $data = $arrInput['data'];
        $objLibrary = new Library();
        $arrDataForm = $this->queryToArray($data);
        $C_OPEN_NEW_WIN = isset($arrDataForm['C_OPEN_NEW_WIN']) ? 1 : 0;
        $C_STATUS = isset($arrDataForm['C_STATUS']) ? 1 : 0;
        if (isset($arrDataForm['PK_CMS_IMAGEADVERTISE']) && $arrDataForm['PK_CMS_IMAGEADVERTISE'] != '') {
            $id = $arrDataForm['PK_CMS_IMAGEADVERTISE'];
        } else {
            $id = Uuid::generate();
        }
        $imagesModel = ImagesModel::find($id);
        if (!isset($imagesModel)) {
            $imagesModel = new ImagesModel();
            $imagesModel->C_INPUT_DATE = date('Y/m/d H:i:s');
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
            $imagesModel->C_IMAGE_FILE_NAME = trim($fileattach, ',');
        }
        $imagesModel->PK_CMS_IMAGEADVERTISE = $id;
        $imagesModel->C_NAME = $arrDataForm['C_NAME'];
        $imagesModel->C_URL = $arrDataForm['C_URL'];
        $imagesModel->C_POSITION = $arrDataForm['C_POSITION'];
        $imagesModel->C_OPEN_NEW_WIN = $C_OPEN_NEW_WIN;
        $imagesModel->C_ORDER = $arrDataForm['C_ORDER'];
        $imagesModel->C_CLASS_NAME = $arrDataForm['C_CLASS_NAME'];
        $imagesModel->C_STYLE = $arrDataForm['C_STYLE'];
        $imagesModel->C_OWNER_CODE = $_SESSION['OWNER_CODE'];
        $imagesModel->C_HEIGHT = $arrDataForm['C_HEIGHT'];
        $imagesModel->C_WIDTH = $arrDataForm['C_WIDTH'];
        $imagesModel->C_BEGIN_DATE = $objLibrary->_ddmmyyyyToYYyymmdd($arrDataForm['C_BEGIN_DATE']);
        $imagesModel->C_END_DATE = $objLibrary->_ddmmyyyyToYYyymmdd($arrDataForm['C_END_DATE']);
        $imagesModel->C_STATUS = $C_STATUS;
        $imagesModel->save();
        return array('success' => true, 'message' => 'Cập nhật thành công', 'parent_id' => $request->parent_id);
    }

    public function images_delete(Request $request) {
        $arrInput = $request->input();
        $idlist = $arrInput['listitem'];
        $sql = "DELETE T_CMS_IMAGEADVERTISE WHERE CHARINDEX(CONVERT(varchar(50),PK_CMS_IMAGEADVERTISE),'$idlist') >0 ";
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

}
