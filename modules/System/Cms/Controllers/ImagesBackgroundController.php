<?php

namespace Modules\System\Cms\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Core\Ncl\Library;
use Modules\System\Cms\Models\ImagesBackgroundModel;
use Modules\System\Listtype\Models\ListModel;
use DB;
use Uuid;

/**
 * Lớp xử lý quản trị, nhóm người dùng
 *
 * @author Toanph <skype: toanph155>
 */
class ImagesBackgroundController extends Controller {

    public function __construct() {
        $check = Library::checkPermissionController('CmsImagesBackgroundController');
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
        $result = $obj->_getAllFileJavaScriptCssArray('js', 'system/Cms/JS_ImagesBackground.js', ',', $result,'JS_ImagesBackground.min.js');
        $data['stringJsCss'] = json_encode($result);
        $arrData = ImagesBackgroundModel::all()->toArray();
        $data['data'] = $arrData;
        return view('Cms::ImagesBackground.index', $data);
    }

    public function loadlist(Request $request) {
        $objLib = new Library();
        $arrInput = $request->input();
        $objArticleModel = new ImagesBackgroundModel();
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
        $objImagesBackgroundModel = new ImagesBackgroundModel();
        $data['C_ORDER'] = $objImagesBackgroundModel->count('*') + 1;
        $arrVitriAnh = $listmodel->_getAllbyCode('DM_LOAI_ANH_BACKGROUND', false, ['C_CODE', 'C_NAME']);
        $data['arrVitriAnh'] = $arrVitriAnh;
        $data['PK_CMS_IMAGES_BACKGROUND'] = '';
        $data['C_STATUS']=1;
        return view('Cms::ImagesBackground.add', $data);
    }

    public function images_edit(Request $request) {
        $data = [];
        $arrInput = $request->input();
        $objLib = new Library();
        $listmodel = new ListModel();
        $objImagesBackgroundModel = new ImagesBackgroundModel();
        $arrSingle = $objImagesBackgroundModel->where('PK_CMS_IMAGES_BACKGROUND', $arrInput['chk_item_id'])->get()->toArray();
        $arrVitriAnh = $listmodel->_getAllbyCode('DM_LOAI_ANH_BACKGROUND', false, ['C_CODE', 'C_NAME']);
        $data['arrVitriAnh'] = $arrVitriAnh;
        if ($arrSingle[0]['C_IMAGE_FILE_NAME'] != '')
            $arrSingle[0]['C_IMAGE_FILE_NAME'] = url('public/cms_attach_file') . '/' . $objLib->_getfolderbyfilename($arrSingle[0]['C_IMAGE_FILE_NAME']);
        $data['arrSingle'] = $arrSingle[0];
        $data['PK_CMS_IMAGES_BACKGROUND'] = $arrSingle[0]['PK_CMS_IMAGES_BACKGROUND'];
        $data['C_ORDER'] = $arrSingle[0]['C_ORDER'];
        $data['objLib'] = $objLib;
        $data['C_STATUS']=$arrSingle[0]['C_STATUS'];
        return view('Cms::ImagesBackground.add', $data);
    }

    public function images_update(Request $request) {
        $arrInput = $request->input();
        $data = $arrInput['data'];
        $objLibrary = new Library();
        $arrDataForm = $this->queryToArray($data);
        $C_OPEN_NEW_WIN = isset($arrDataForm['C_OPEN_NEW_WIN']) ? 1 : 0;
        $C_STATUS = isset($arrDataForm['C_STATUS']) ? 1 : 0;
        if (isset($arrDataForm['PK_CMS_IMAGES_BACKGROUND']) && $arrDataForm['PK_CMS_IMAGES_BACKGROUND'] != '') {
            $id = $arrDataForm['PK_CMS_IMAGES_BACKGROUND'];
        } else {
            $id = Uuid::generate();
        }
        $imagesModel = ImagesBackgroundModel::find($id);
        if (!isset($imagesModel)) {
            $imagesModel = new ImagesBackgroundModel();
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
        $imagesModel->PK_CMS_IMAGES_BACKGROUND = $id;
        $imagesModel->C_NAME = $arrDataForm['C_NAME'];
        $imagesModel->C_POSITION = $arrDataForm['C_POSITION'];
        $imagesModel->C_ORDER = $arrDataForm['C_ORDER'];
        $imagesModel->C_CLASS_NAME = $arrDataForm['C_CLASS_NAME'];
        $imagesModel->C_STYLE = $arrDataForm['C_STYLE'];
        $imagesModel->C_OWNER_CODE = $_SESSION['OWNER_CODE'];
        $imagesModel->C_HEIGHT = $arrDataForm['C_HEIGHT'];
        $imagesModel->C_WIDTH = $arrDataForm['C_WIDTH'];
        $imagesModel->C_URL = $arrDataForm['C_URL'];
        $imagesModel->C_BEGIN_DATE = $objLibrary->_ddmmyyyyToYYyymmdd($arrDataForm['C_BEGIN_DATE']);
        $imagesModel->C_END_DATE = $objLibrary->_ddmmyyyyToYYyymmdd($arrDataForm['C_END_DATE']);
        $imagesModel->C_STATUS = $C_STATUS;
        $imagesModel->save();
        return array('success' => true, 'message' => 'Cập nhật thành công', 'parent_id' => $request->parent_id);
    }

    public function images_delete(Request $request) {
        $arrInput = $request->input();
        $idlist = $arrInput['listitem'];
        $sql = "DELETE T_CMS_IMAGES_BACKGROUND WHERE CHARINDEX(CONVERT(varchar(50),PK_CMS_IMAGES_BACKGROUND),'$idlist') >0 ";
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
