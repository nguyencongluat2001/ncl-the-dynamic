<?php

namespace Modules\System\Cms\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Core\EFY\Library;
use Modules\System\Cms\Models\ArticlesBookModel;
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
class ArticlesBookController extends Controller {

    public function __construct() {
        $check = Library::checkPermissionController('CmsArticlesController');
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
        $result = $obj->_getAllFileJavaScriptCssArray('js', 'system/Cms/JS_ArticlesBook.js', ',', $result, 'JS_ArticlesBook.min.js');
        $result = $obj->_getAllFileJavaScriptCssArray('js', 'assets/jstree/jstree.min.js,assets/jstree/jstreetable.js,assets/datepicker/bootstrap-datepicker.min.js,assets/datepicker/bootstrap-datepicker.vi.js', ',', $result);
        $result = $obj->_getAllFileJavaScriptCssArray('js', 'jquery.autocomplete.js', ',', $result);
        $result = $obj->_getAllFileJavaScriptCssArray('js', 'assets/ckeditor/ckeditor.js', ',', $result);
        $result = $obj->_getAllFileJavaScriptCssArray('css', 'assets/tree/style.min.css', ',', $result);
        $result = $obj->_getAllFileJavaScriptCssArray('css', 'jquery.autocomplete.css', ',', $result);
        $data['stringJsCss'] = json_encode($result);
//        $arrTrangThaiTinBai = $ListModel->_getAllbyCode('DM_TRANG_THAI_TIN_BAI', false, ["C_CODE", "C_NAME"]);
//        $data['arrTrangThaiTinBai'] = $arrTrangThaiTinBai;
        return view('Cms::ArticlesBook.index', $data);
    }

    public function loadlist(Request $request) {
        $arrInput = $request->input();
        $objLib = new Library();
        $fromdate = $objLib->_ddmmyyyyToYYyymmdd($arrInput['fromdate']);
        $todate = $objLib->_ddmmyyyyToYYyymmdd($arrInput['todate']);
        $search = $arrInput['search'];
        $objArticleModel = new ArticlesBookModel();
        $objResult = $objArticleModel->_getAll($arrInput['currentPage'], $arrInput['perPage'], $search, $fromdate, $todate);
        return \Response::JSON(array(
                    'Dataloadlist' => $objResult,
                    'pagination' => (string) $objResult->links('Cms::vendor.pagination.default'),
                    'perPage' => $arrInput['perPage'],
        ));
    }

    public function articlesbook_add() {
        $data = [];
        $sql = "select C_CODE,C_NAME,PK_UNIT from T_USER_UNIT where FK_UNIT in (select PK_UNIT from T_USER_UNIT where FK_UNIT = (SELECT PK_UNIT from T_USER_UNIT where FK_UNIT is null))";
        $arrUnit = DB::select($sql);
        $ListModel = new ListModel();
        $arrLoaiTinBai = $ListModel->_getAllbyCode('DM_LOAI_TIN_BAI', false, ["C_CODE", "C_NAME"]);
        $arrTrangThaiTinBai = $ListModel->_getAllbyCode('DM_TRANG_THAI_TIN_BAI', false, ["C_CODE", "C_NAME"]);
        $sqlGetAuthor = "select distinct C_AUTHOR from T_CMS_ARTICLES where C_AUTHOR != ''";
        $arrAuthor = DB::select($sqlGetAuthor);
        $arrAuthorReturn = array();
        foreach ($arrAuthor as $key => $v) {
            array_push($arrAuthorReturn, $v->C_AUTHOR);
        }
        $data['arrAuthorReturn'] = $arrAuthorReturn;
        $data['arrUnit'] = $arrUnit;
        $data['arrLoaiTinBai'] = $arrLoaiTinBai;
        $data['arrTrangThaiTinBai'] = $arrTrangThaiTinBai;
        return view('Cms::ArticlesBook.add', $data);
    }

    public function articlesbook_edit(Request $request) {
        $data = [];
        $arrInput = $request->input();
        $objLib = new Library();
        $objArticlesBookModel = new ArticlesBookModel();
        $ListModel = new ListModel();
        $arrLoaiTinBai = $ListModel->_getAllbyCode('DM_LOAI_TIN_BAI', false, ["*"]);
        $arrTrangThaiTinBai = $ListModel->_getAllbyCode('DM_TRANG_THAI_TIN_BAI', false, ["C_CODE", "C_NAME"]);
        
        $arrSingle = $objArticlesBookModel->join('eLIB_PUBLICATION','CMS_ARTICLES_BOOK.FK_PUBLICATION','=','eLIB_PUBLICATION.PK_PUBLICATION')->where('PK_CMS_ARTICLE_BOOK', $arrInput['chk_item_id'])->select(['CMS_ARTICLES_BOOK.*','eLIB_PUBLICATION.C_FULL_NAME'])->get()->toArray();
        $arrSingle[0]['C_CREATE_DATE'] = $objLib->_yyyymmddToDDmmyyyy($arrSingle[0]['C_CREATE_DATE'], true);
        $arrSingle[0]['C_FEATURE_IMG_BASE'] = $arrSingle[0]['C_FEATURE_IMG'];
        $arrSingle[0]['C_FEATURE_IMG'] = url('public/cms_attach_file/' . $objLib->_getfolderbyfilename($arrSingle[0]['C_FEATURE_IMG']));
        $sql = "select C_CODE,C_NAME,PK_UNIT from T_USER_UNIT where FK_UNIT in (select PK_UNIT from T_USER_UNIT where FK_UNIT = (SELECT PK_UNIT from T_USER_UNIT where FK_UNIT is null))";
        $arrUnit = DB::select($sql);
        $sqlGetAuthor = "select distinct C_AUTHOR from T_CMS_ARTICLES where C_AUTHOR != ''";
        $arrAuthor = DB::select($sqlGetAuthor);
        $arrAuthorReturn = array();
        foreach ($arrAuthor as $key => $v) {
            array_push($arrAuthorReturn, $v->C_AUTHOR);
        }
        $data['arrAuthorReturn'] = $arrAuthorReturn;
        $data['arrUnit'] = $arrUnit;
        $data['arrSingle'] = $arrSingle[0];
        $data['objLib'] = $objLib;
        $data['arrLoaiTinBai'] = $arrLoaiTinBai;
        $data['arrTrangThaiTinBai'] = $arrTrangThaiTinBai;
        return view('Cms::ArticlesBook.add', $data);
    }

    public function articlesbook_update(Request $request) {
        $arrInput = $request->input();
        $data = $arrInput['data'];
        $objLibrary = new Library();
        $arrDataForm = $this->queryToArray($data);
        $status = isset($arrDataForm['C_STATUS']) ? 'HOAT_DONG' : 'KHONG_HOAT_DONG';
        $C_IS_COMMENT = isset($arrDataForm['C_IS_COMMENT']) ? 1 : 0;
        $C_IS_HIDE_RELATE_ATICLES = isset($arrDataForm['C_IS_HIDE_RELATE_ATICLES']) ? 1 : 0;
        if (isset($arrDataForm['PK_ARTICLES']) && $arrDataForm['PK_ARTICLES'] != '') {
            $id = $arrDataForm['PK_ARTICLES'];
        } else {
            $id = Uuid::generate();
        }
        $articlesModel = ArticlesBookModel::find($id);
        if (!isset($articlesModel)) {
            $articlesModel = new ArticlesBookModel();
            $articlesModel->C_INPUT_DATE = date('Y/m/d H:i:s');
        }
        $fileattach = '';
        $featurefile = '';
        if ($_FILES) {
            $basepath = base_path('public\cms_attach_file\\');
            $year = date('Y');
            $month = date('m');
            $date = date('d');
            $folder = $objLibrary->_createFolder($basepath, $year, $month, $date);
            if (isset($_FILES['C_FEATURE_IMG'])) {
                $featureimage = $_FILES['C_FEATURE_IMG'];
                $filename = $year . '_' . $month . '_' . $date . '_' . time() . '!~!' . $featureimage['name'];
                $filename = str_replace(' ', '_', $filename);
                copy($featureimage['tmp_name'], $folder . $filename);
                $featurefile = $filename;
                $articlesModel->C_FEATURE_IMG = $featurefile;
            }

            foreach ($_FILES as $key => $value) {
                if ($key != 'C_FEATURE_IMG') {
                    $filename = $year . '_' . $month . '_' . $date . '_' . time() . '!~!' . $value['name'];
                    $filename = str_replace(' ', '_', $filename);
                    copy($value['tmp_name'], $folder . $filename);
                    $fileattach .= ',' . $filename;
                }
            }
            $articlesModel->C_FILE_NAME = trim($articlesModel->C_FILE_NAME .= trim($fileattach, ','));
        }
        $articlesModel->PK_CMS_ARTICLE_BOOK = $id;
        $articlesModel->FK_CATEGORY = $arrInput['FK_CATEGORY'];
        $articlesModel->FK_CREATE_STAFF = $_SESSION['user_infor']['PK_STAFF'];
        $articlesModel->C_CREATE_STAFF_NAME = $_SESSION['user_infor']['C_NAME'];
        $articlesModel->C_TITLE = $arrDataForm['C_TITLE'];
        $articlesModel->C_SUBJECT = $arrDataForm['C_SUBJECT'];
        $articlesModel->C_SLUG = $arrDataForm['C_SLUG'];
        $articlesModel->C_CREATE_DATE = $objLibrary->_ddmmyyyyToYYyymmdd($arrDataForm['C_CREATE_DATE']);
        $articlesModel->C_AUTHOR = $arrDataForm['C_AUTHOR'];
        $articlesModel->C_TITLE_SEO = $arrDataForm['C_TITLE_SEO'];
        $articlesModel->C_DESCRIPTION_SEO = $arrDataForm['C_DESCRIPTION_SEO'];
        $articlesModel->C_STATUS_ARTICLES_BOOK = $arrDataForm['C_STATUS_ARTICLES'];
        $articlesModel->C_ARTICLES_BOOK_TYPE = $arrDataForm['C_ARTICLES_TYPE'];
        $articlesModel->FK_PUBLICATION = $arrDataForm['FK_PUBLICATION'];
        $articlesModel->C_CONTENT = $arrInput['C_CONTENT'];
        $articlesModel->C_ORDER = $arrDataForm['C_ORDER'];
        $articlesModel->C_STATUS = $status;
        $articlesModel->C_IS_COMMENT = $C_IS_COMMENT;
        $articlesModel->C_IS_HIDE_RELATE_ATICLES = $C_IS_HIDE_RELATE_ATICLES;
        $articlesModel->C_OWNER_CODE = $_SESSION['OWNER_CODE'];
        $articlesModel->C_SOURCE =  $arrDataForm['C_SOURCE'];
        $articlesModel->save();
        return array('success' => true, 'message' => 'Cập nhật thành công', 'parent_id' => $request->parent_id);
    }

    public function articlesbook_delete(Request $request) {
        $arrInput = $request->input();
        $idlist = $arrInput['listitem'];
        $sql = "DELETE T_CMS_ARTICLES_BOOK WHERE CHARINDEX(CONVERT(varchar(50),PK_CMS_ARTICLE_BOOK),'$idlist') >0 ";
        DB::delete($sql);
        return array('success' => true, 'message' => 'Cập nhật thành công', 'parent_id' => $request->parent_id);
    }

    public function gennerateTreeCategories($list_fkcategories = '', $ownercode) {
        $list_category = implode(',', $_SESSION['C_LIST_CATEGORY']);
        if ($_SESSION['role'] == 'USER') {
            $sql = "SELECT DBO.F_CHECKLASTCATEGORY(PK_CATEGORIES) AS C_IS_LAST_ITEM,PK_CATEGORIES,FK_CATEGORIES,C_NAME,C_SLUG FROM T_CMS_CATEGORIES WHERE FK_CATEGORIES  = (SELECT PK_CATEGORIES FROM T_CMS_CATEGORIES WHERE FK_CATEGORIES IS NULL) AND C_CATEGORY_TYPE ='CHUYEN_MUC_BAI_VIET' AND C_OWNER_CODE = '$ownercode' AND ( CHARINDEX(CONVERT(VARCHAR(50),FK_CATEGORIES),'$list_category')>0 OR CHARINDEX(CONVERT(VARCHAR(50),PK_CATEGORIES),'$list_category')>0 ) AND  C_STATUS ='HOAT_DONG' ORDER BY C_ORDER";
        } else {
            $sql = "SELECT DBO.F_CHECKLASTCATEGORY(PK_CATEGORIES) AS C_IS_LAST_ITEM,PK_CATEGORIES,FK_CATEGORIES,C_NAME,C_SLUG FROM T_CMS_CATEGORIES WHERE FK_CATEGORIES  = (SELECT PK_CATEGORIES FROM T_CMS_CATEGORIES WHERE FK_CATEGORIES IS NULL) AND C_CATEGORY_TYPE ='CHUYEN_MUC_BAI_VIET' AND C_OWNER_CODE = '$ownercode' AND  C_STATUS ='HOAT_DONG' ORDER BY C_ORDER";
        }

        $arrCateRoot = DB::select($sql);
        $shtmlTree = '<ul>';
        foreach ($arrCateRoot as $key => $value) {
            $selected = "";
            if (strpos("EFY_" . $list_fkcategories, $value->PK_CATEGORIES) > 0) {
                $selected = "true";
            }
            if ($value->C_IS_LAST_ITEM == '0') {
                $disabled = "\"disabled\":\"0\"";
            } else {
                $disabled = "\"\":\"\"";
            }
            $shtmlTree .= "<li id='" . $value->PK_CATEGORIES . "' data-jstree='{ \"icon\":\"fa fa fa-university\", $disabled ,\"state\" : { \"checkbox_disabled\" : true },\"selected\":\"$selected\" }'  slug=\"" . $value->C_SLUG . "\" is_last_item=\"" . $value->C_IS_LAST_ITEM . "\">" . $value->C_NAME;
            if ($value->C_IS_LAST_ITEM == '0') {
                if ($_SESSION['role'] == 'USER') {
                    $sql = "SELECT DBO.F_CHECKLASTCATEGORY(PK_CATEGORIES) AS C_IS_LAST_ITEM,PK_CATEGORIES,FK_CATEGORIES,C_NAME,C_SLUG FROM T_CMS_CATEGORIES WHERE FK_CATEGORIES  = '" . $value->PK_CATEGORIES . "' AND C_CATEGORY_TYPE ='CHUYEN_MUC_BAI_VIET' AND C_OWNER_CODE = '$ownercode' AND ( CHARINDEX(CONVERT(VARCHAR(50),FK_CATEGORIES),'$list_category')>0 OR CHARINDEX(CONVERT(VARCHAR(50),PK_CATEGORIES),'$list_category')>0 ) AND C_STATUS ='HOAT_DONG' ORDER BY C_ORDER";
                } else {
                    $sql = "SELECT DBO.F_CHECKLASTCATEGORY(PK_CATEGORIES) AS C_IS_LAST_ITEM,PK_CATEGORIES,FK_CATEGORIES,C_NAME,C_SLUG FROM T_CMS_CATEGORIES WHERE FK_CATEGORIES  = '" . $value->PK_CATEGORIES . "' AND C_CATEGORY_TYPE ='CHUYEN_MUC_BAI_VIET' AND C_OWNER_CODE = '$ownercode' AND C_STATUS ='HOAT_DONG' ORDER BY C_ORDER";
                }
                $arrCategoriesCap1 = DB::select($sql);
                $shtmlTree .= "<ul>";
                foreach ($arrCategoriesCap1 as $key1 => $value1) {
                    $selected = "";
                    if (strpos("EFY_" . $list_fkcategories, $value1->PK_CATEGORIES) > 0) {
                        $selected = "true";
                    }
                    $shtmlTree .= "<li id='" . $value1->PK_CATEGORIES . "' data-jstree='{ \"icon\":\"fa fa-newspaper-o\" ,\"selected\":\"$selected\" }' slug=\"" . $value1->C_SLUG . "\" is_last_item=\"" . $value1->C_IS_LAST_ITEM . "\">";
                    $shtmlTree .= $value1->C_NAME;
                    if ($value1->C_IS_LAST_ITEM == '0') {
                        if ($_SESSION['role'] == 'USER') {
                            $sql = "SELECT DBO.F_CHECKLASTCATEGORY(PK_CATEGORIES) AS C_IS_LAST_ITEM,PK_CATEGORIES,FK_CATEGORIES,C_NAME,C_SLUG FROM T_CMS_CATEGORIES WHERE FK_CATEGORIES  = '" . $value1->PK_CATEGORIES . "' AND C_CATEGORY_TYPE ='CHUYEN_MUC_BAI_VIET' AND C_OWNER_CODE = '$ownercode' AND ( CHARINDEX(CONVERT(VARCHAR(50),FK_CATEGORIES),'$list_category')>0 AND  C_STATUS ='HOAT_DONG' ORDER BY C_ORDER";
                        } else {
                            $sql = "SELECT DBO.F_CHECKLASTCATEGORY(PK_CATEGORIES) AS C_IS_LAST_ITEM,PK_CATEGORIES,FK_CATEGORIES,C_NAME,C_SLUG FROM T_CMS_CATEGORIES WHERE FK_CATEGORIES  = '" . $value1->PK_CATEGORIES . "' AND C_CATEGORY_TYPE ='CHUYEN_MUC_BAI_VIET' AND C_OWNER_CODE = '$ownercode' AND  C_STATUS ='HOAT_DONG' ORDER BY C_ORDER";
                        }

                        $arrCategoriesCap2 = DB::select($sql);
                        $shtmlTree .= "<ul>";
                        foreach ($arrCategoriesCap2 as $key2 => $value2) {
                            $selected = "";
                            if (strpos("EFY_" . $list_fkcategories, $value2->PK_CATEGORIES) > 0) {
                                $selected = "true";
                            }
                            $shtmlTree .= "<li id='" . $value2->PK_CATEGORIES . "' data-jstree='{ \"icon\":\"fa fa-newspaper-o\" ,\"selected\":\"$selected\" }' slug=\"" . $value2->C_SLUG . "\" is_last_item=\"" . $value2->C_IS_LAST_ITEM . "\" >";
                            $shtmlTree .= $value2->C_NAME;
                        }
                        $shtmlTree .= "</ul>";
                    }
                    $shtmlTree .= "</li>";
                }
                $shtmlTree .= "</ul>";
            }
            $shtmlTree .= "</li>";
        }
        $shtmlTree .= "</ul>";
        return $shtmlTree;
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

    public function search_documents(Request $rq) {
        return view('Cms::ArticlesBook.searchdocumentindex', []);
    }

    public function load_document(Request $request) {
        $objLib = new Library();
        $arrInput = $request->input();
        $PublicationModel = new \Modules\Frontend\Cms\Models\PublicationModel();
        $objResult = $PublicationModel->_getAllArticlesBook($arrInput['currentPage'], $arrInput['perPage'], $arrInput['search']);
        $arrData = $objResult->toArray();
        $arrData = $this->convertArrdata($arrData);
        return \Response::JSON(array(
                    'Dataloadlist' => $arrData,
                    'pagination' => (string) $objResult->links('Cms::vendor.pagination.default'),
                    'perPage' => $arrInput['perPage'],
        ));
    }

    public function convertArrdata($arrData) {
        for ($i = 0; $i < sizeof($arrData['data']); $i++) {
            $ext1 = $ext2 = $ext3 = $tg = $nxb = '';
            $arrJsonData = json_decode($arrData['data'][$i]['C_DATA_FORM'], true);
            if (is_array($arrJsonData)) {
                foreach ($arrJsonData as $key => $value) {
                    if (is_array($value)) {
                        foreach ($value as $k => $v) {
                            if ($k == 'CHILDFIELD_148A6020-CFD7-11EA-918F-3572DA3297A0_b') {
                                $ext1 = $v;
                            }
                            if ($k == 'CHILDFIELD_148A6020-CFD7-11EA-918F-3572DA3297A0_n') {
                                $ext2 = $v;
                            }
                            if ($k == 'CHILDFIELD_148A6020-CFD7-11EA-918F-3572DA3297A0_p') {
                                $ext3 = $v;
                            }
                            if ($k == 'CHILDFIELD_032FC410-CFD7-11EA-B861-0D3039C0F3DE_a') {
                                $tg = $v;
                            }
                            if ($k == 'CHILDFIELD_A1F672E0-CFD8-11EA-AA0A-97F532A7E9FE_b') {
                                $nxb = $v;
                                $sql = "Select name from t_system_list where listtype_id = 4033 and code = N'$nxb'";
                                $arrNxb = DB::select($sql);
                                if (isset($arrNxb[0]))
                                    $nxb = $arrNxb[0]->name;
                            }
                        }
                    }
                }
            }
            $extend = $ext1 . ' ' . $ext2 . ' ' . $ext3;
            $arrData['data'][$i]['EXTEND_NHANDE'] = $extend;
            $arrData['data'][$i]['C_TAC_GIA'] = str_replace('#EFY#', ';', $tg);
            $arrData['data'][$i]['NHA_XUAT_BAN'] = $nxb;
        }
        return $arrData;
    }
    // public function manager_comment(Request $request) {
    //     $arrInput = $request->input();
    //     $data['FK_ARTICLE'] = $arrInput['chk_item_id'];
    //     return view('Cms::articles.manager-comment', $data);
    // }

    // public function loadlist_comment(Request $request) {
    //     $objLib = new Library();
    //     $arrInput = $request->input();
    //     $FK_ARTICLE = $arrInput['FK_ARTICLE'];
    //     $ArticlesCommentModel = new \Modules\System\Cms\Models\ArticlesCommentModel();
    //     $objResult = $ArticlesCommentModel->_getAll($FK_ARTICLE, $arrInput['currentPage'], $arrInput['perPage'], $arrInput['search']);
    //     $arrData = $objResult->toArray();
    //     for ($i = 0; $i < sizeof($arrData['data']); $i++) {
    //         $arrData['data'][$i]['C_FILE_NAME'] = url('public/cms_attach_file') . '/' . $objLib->_getfolderbyfilename($arrData['data'][$i]['C_FILE_NAME']);
    //     }
    //     return \Response::JSON(array(
    //                 'Dataloadlist' => $arrData,
    //                 'pagination' => (string) $objResult->links('Cms::vendor.pagination.default'),
    //                 'perPage' => $arrInput['perPage'],
    //     ));
    // }

    // public function see_comment(Request $request) {
    //     $arrInput = $request->input();
    //     $id_comment = $arrInput['chk_item_id'];
    //     $ArticlesCommentModel = new \Modules\System\Cms\Models\ArticlesCommentModel();
    //     $arrSingle = $ArticlesCommentModel->where('PK_ARTICLES_COMMENT', '=', $id_comment)->get()->toArray();
    //     $data['arrSingle'] = $arrSingle[0];
    //     return view('Cms::articles.see-comment', $data);
    // }

    // public function approve_comment(Request $request) {
    //     $arrInput = $request->input();
    //     $ArticlesCommentModel = new \Modules\System\Cms\Models\ArticlesCommentModel();
    //     $id_comment = $arrInput['PK_ARTICLES_COMMENT'];
    //     $ArticlesCommentModel->where('PK_ARTICLES_COMMENT', '=', $id_comment)->update(['C_STATUS' => 'HIEN_THI']);
    //     return array('success' => true, 'message' => 'Cập nhật thành công');
    // }

    // public function delete_comment(Request $request) {
    //     $arrInput = $request->input();
    //     $idlist = $arrInput['listitem'];
    //     $sql = "DELETE T_CMS_ARTICLES_COMMENT WHERE CHARINDEX(CONVERT(varchar(50),PK_ARTICLES_COMMENT),'$idlist') >0 ";
    //     DB::delete($sql);
    //     return array('success' => true, 'message' => 'Xóa bình luận thành công');
    // }

}
