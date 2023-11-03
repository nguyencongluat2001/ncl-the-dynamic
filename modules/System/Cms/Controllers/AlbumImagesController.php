<?php

namespace Modules\System\Cms\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Core\Ncl\Library;
use Modules\System\Cms\Models\AlbumImagesModel;
use Modules\System\Cms\Models\AlbumRelateImageModel;
use Modules\System\Listtype\Models\ListModel;
use Modules\System\Users\Models\UnitModel;
use DB;
use Uuid;

/**
 * Lớp xử lý quản trị, nhóm người dùng
 *
 * @author Toanph <skype: toanph155>
 */
class AlbumImagesController extends Controller {

    public function __construct() {
        $check = Library::checkPermissionController('CmsAlbumImagesController');
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
        $result = $obj->_getAllFileJavaScriptCssArray('js', 'system/Cms/JS_AlbumImages.js', ',', $result, 'JS_AlbumImages.min.js');
        $data['stringJsCss'] = json_encode($result);
        $arrData = AlbumImagesModel::all()->toArray();
        $data['data'] = $arrData;
        return view('Cms::Album-images.index', $data);
    }

    public function loadlist(Request $request) {
        $objLib = new Library();
        $arrInput = $request->input();
        $objArticleModel = new AlbumImagesModel();
        $objResult = $objArticleModel->_getAll($arrInput['currentPage'], $arrInput['perPage'], $arrInput['search']);
        $arrData = $objResult->toArray();
        for ($i = 0; $i < sizeof($arrData['data']); $i++) {
            if (strpos('EFF' . $arrData['data'][$i]['C_IMAGE_FILE_NAME'], 'http') > 0) {
                
            } else {
                $arrData['data'][$i]['C_IMAGE_FILE_NAME'] = url('public/cms_attach_file') . '/' . $objLib->_getfolderbyfilename($arrData['data'][$i]['C_IMAGE_FILE_NAME']);
            }
        }
        return \Response::JSON(array(
                    'Dataloadlist' => $arrData,
                    'pagination' => (string) $objResult->links('Cms::vendor.pagination.default'),
                    'perPage' => $arrInput['perPage'],
        ));
    }

    public function albumimages_add() {
        $data = [];
        $listmodel = new ListModel();
        $objAlbumImagesModel = new AlbumImagesModel();
        $data['C_ORDER'] = $objAlbumImagesModel->count('*') + 1;
        $arrVitriAnh = $listmodel->_getAllbyCode('DM_VI_TRI_ANH', false, ['C_CODE', 'C_NAME']);
        $data['arrVitriAnh'] = $arrVitriAnh;
        $data['PK_CMS_ALBUM_IMAGES'] = '';
        $data['C_STATUS'] = '1';
        return view('Cms::Album-images.add', $data);
    }

    public function albumimages_edit(Request $request) {
        $data = [];
        $arrInput = $request->input();
        $objLib = new Library();
        $listmodel = new ListModel();
        $objAlbumImagesModel = new AlbumImagesModel();
        $arrSingle = $objAlbumImagesModel->where('PK_CMS_ALBUM_IMAGES', $arrInput['chk_item_id'])->get()->toArray();
        $arrVitriAnh = $listmodel->_getAllbyCode('DM_VI_TRI_ANH', false, ['C_CODE', 'C_NAME']);
        $data['arrVitriAnh'] = $arrVitriAnh;
        if ($arrSingle[0]['C_IMAGE_FILE_NAME'] != '') {
            if (strpos('EFF' . $arrSingle[0]['C_IMAGE_FILE_NAME'], 'http') > 0) {
                
            } else {
                $arrSingle[0]['C_IMAGE_FILE_NAME'] = url('public/cms_attach_file') . '/' . $objLib->_getfolderbyfilename($arrSingle[0]['C_IMAGE_FILE_NAME']);
            }
        }

        $data['arrSingle'] = $arrSingle[0];
        $data['PK_CMS_ALBUM_IMAGES'] = $arrSingle[0]['PK_CMS_ALBUM_IMAGES'];
        $data['C_ORDER'] = $arrSingle[0]['C_ORDER'];
        $data['objLib'] = $objLib;
        $data['C_STATUS'] = $arrSingle[0]['C_STATUS'];
        return view('Cms::Album-images.add', $data);
    }

    public function albumimages_update(Request $request) {
        $arrInput = $request->input();
        $data = $arrInput['data'];
        $objLibrary = new Library();
        $arrDataForm = $this->queryToArray($data);
        $C_OPEN_NEW_WIN = isset($arrDataForm['C_OPEN_NEW_WIN']) ? 1 : 0;
        $C_STATUS = isset($arrDataForm['C_STATUS']) ? 1 : 0;
        if (isset($arrDataForm['PK_CMS_ALBUM_IMAGES']) && $arrDataForm['PK_CMS_ALBUM_IMAGES'] != '') {
            $id = $arrDataForm['PK_CMS_ALBUM_IMAGES'];
        } else {
            $id = Uuid::generate();
        }
        $imagesModel = AlbumImagesModel::find($id);
        if (!isset($imagesModel)) {
            $imagesModel = new AlbumImagesModel();
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
        if ($arrDataForm['image_onserver'] != '') {
            $imagesModel->C_IMAGE_FILE_NAME = $arrDataForm['image_onserver'];
        }
        $imagesModel->PK_CMS_ALBUM_IMAGES = $id;
        $imagesModel->C_NAME = $arrDataForm['C_NAME'];
        $imagesModel->C_URL = $arrDataForm['C_URL'];
        $imagesModel->C_ORDER = $arrDataForm['C_ORDER'];
        $imagesModel->C_OWNER_CODE = $_SESSION['OWNER_CODE'];
        $imagesModel->C_BEGIN_DATE = $objLibrary->_ddmmyyyyToYYyymmdd($arrDataForm['C_BEGIN_DATE']);
        $imagesModel->C_END_DATE = $objLibrary->_ddmmyyyyToYYyymmdd($arrDataForm['C_END_DATE']);
        $imagesModel->C_STATUS = $C_STATUS;
        $imagesModel->save();
        return array('success' => true, 'message' => 'Cập nhật thành công', 'parent_id' => $request->parent_id);
    }

    public function update_image(Request $request) {
        $arrInput = $request->input();
        $data = $arrInput['data'];
        $objLibrary = new Library();
        $arrDataForm = $this->queryToArray($data);
        $C_STATUS = isset($arrDataForm['C_STATUS']) ? 1 : 0;
        if (isset($arrDataForm['PK_CMS_RELATE_IMAGE_ALBUM']) && $arrDataForm['PK_CMS_RELATE_IMAGE_ALBUM'] != '') {
            $id = $arrDataForm['PK_CMS_RELATE_IMAGE_ALBUM'];
        } else {
            $id = Uuid::generate();
        }
        $imagesModel = AlbumRelateImageModel::find($id);
        if (!isset($imagesModel)) {
            $imagesModel = new AlbumRelateImageModel();
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
        if ($arrDataForm['image_onserver'] != '') {
            $imagesModel->C_IMAGE_FILE_NAME = $arrDataForm['image_onserver'];
        }
        $imagesModel->PK_CMS_RELATE_IMAGE_ALBUM = $id;
        $imagesModel->FK_ALBUM_IMAGE = $arrDataForm['FK_ALBUM_IMAGE'];
        $imagesModel->C_NAME = $arrDataForm['C_NAME'];
        $imagesModel->C_ORDER = $arrDataForm['C_ORDER'];
        $imagesModel->C_HEIGHT = $arrDataForm['C_HEIGHT'];
        $imagesModel->C_WIDTH = $arrDataForm['C_WIDTH'];
        $imagesModel->C_STATUS = $C_STATUS;
        $imagesModel->save();
        return array('success' => true, 'message' => 'Cập nhật thành công', 'parent_id' => $request->parent_id);
    }

    public function albumimages_delete(Request $request) {
        $arrInput = $request->input();
        $idlist = $arrInput['listitem'];
        $sql = "DELETE T_CMS_ALBUM_IMAGES WHERE CHARINDEX(CONVERT(varchar(50),PK_CMS_ALBUM_IMAGES),'$idlist') >0 ";
        DB::delete($sql);
        return array('success' => true, 'message' => 'Cập nhật thành công', 'parent_id' => $request->parent_id);
    }

    public function albumimages_manager(Request $request) {
        $arrInput = $request->input();
        $data['FK_ALBUM_IMAGE'] = $arrInput['chk_item_id'];
        return view('Cms::Album-images.manager-image', $data);
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

    public function loadlist_image(Request $request) {
        $objLib = new Library();
        $arrInput = $request->input();
        $FK_ALBUM_IMAGE = $arrInput['FK_ALBUM_IMAGE'];
        $AlbumRelateImageModel = new AlbumRelateImageModel();
        $objResult = $AlbumRelateImageModel->_getAll($FK_ALBUM_IMAGE, $arrInput['currentPage'], $arrInput['perPage'], $arrInput['search_child']);
        $arrData = $objResult->toArray();
        for ($i = 0; $i < sizeof($arrData['data']); $i++) {
            if (strpos('EFF' . $arrData['data'][$i]['C_IMAGE_FILE_NAME'], 'http') > 0) {
                
            } else {
                $arrData['data'][$i]['C_IMAGE_FILE_NAME'] = url('public/cms_attach_file') . '/' . $objLib->_getfolderbyfilename($arrData['data'][$i]['C_IMAGE_FILE_NAME']);
            }
            
        }
        return \Response::JSON(array(
                    'Dataloadlist' => $arrData,
                    'pagination' => (string) $objResult->links('Cms::vendor.pagination.default'),
                    'perPage' => $arrInput['perPage'],
        ));
    }

    public function image_add(Request $rq) {
        $arrInput = $rq->input();
        $data = [];
        $AlbumRelateImageModel = new AlbumRelateImageModel();
        $data['C_ORDER'] = $AlbumRelateImageModel->where('FK_ALBUM_IMAGE', '=', $arrInput['FK_ALBUM_IMAGE'])->count('*') + 1;

        $data['FK_ALBUM_IMAGE'] = $arrInput['FK_ALBUM_IMAGE'];
        $data['PK_CMS_RELATE_IMAGE_ALBUM'] = '';
        $data['C_STATUS'] = 1;
        // $data['C_OPEN_NEW_WIN']=1;
        return view('Cms::Album-images.image-add', $data);
    }

    public function image_edit(Request $rq) {
        $objLib = new Library();
        $arrInput = $rq->input();
        $data = [];
        $AlbumRelateImageModel = new AlbumRelateImageModel();
        $arrSingle = $AlbumRelateImageModel->where('PK_CMS_RELATE_IMAGE_ALBUM', $arrInput['chk_item_id'])->get()->toArray();
        $data['C_ORDER'] = $arrSingle[0]['C_ORDER'];
        $data['FK_ALBUM_IMAGE'] = $arrSingle[0]['FK_ALBUM_IMAGE'];
        if ($arrSingle[0]['C_IMAGE_FILE_NAME'] != '') {
            if (strpos('EFF' . $arrSingle[0]['C_IMAGE_FILE_NAME'], 'http') > 0) {
                
            } else {
                $arrSingle[0]['C_IMAGE_FILE_NAME'] = url('public/cms_attach_file') . '/' . $objLib->_getfolderbyfilename($arrSingle[0]['C_IMAGE_FILE_NAME']);
            }
        }
        $data['PK_CMS_RELATE_IMAGE_ALBUM'] = $arrSingle[0]['PK_CMS_RELATE_IMAGE_ALBUM'];
        $data['arrSingle'] = $arrSingle[0];
        $data['C_STATUS'] = $arrSingle[0]['C_STATUS'];
        // $data['C_OPEN_NEW_WIN']=$arrSingle[0]['C_OPEN_NEW_WIN'];
        return view('Cms::Album-images.image-add', $data);
    }

    public function image_delete(Request $request) {
        $arrInput = $request->input();
        $idlist = $arrInput['listitem'];
        $sql = "DELETE T_CMS_RELATE_IMAGE_ALBUM WHERE CHARINDEX(CONVERT(varchar(50),PK_CMS_RELATE_IMAGE_ALBUM),'$idlist') >0 ";
        DB::delete($sql);
        return array('success' => true, 'message' => 'Cập nhật thành công', 'parent_id' => $request->parent_id);
    }

}
