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
use Modules\System\Cms\Services\ArticlesService;
use Modules\System\Listtype\Helpers\ListtypeHelper;
use Uuid;

/**
 * Lớp xử lý quản trị, nhóm người dùng
 *
 * @author Toanph <skype: toanph155>
 */
class ArticlesController extends Controller
{
    private $articlesService;

    public function __construct(ArticlesService $articlesService)
    {
        $this->articlesService = $articlesService;
    }

    /**
     * khởi tạo dữ liệu mẫu, Load các file js, css của đối tượng
     *
     * @return view
     */
    public function index()
    {
        $data = $this->articlesService->index();
        return view('Cms::Articles.index', $data);
    }

    public function loadlist(Request $request)
    {
        $data = $this->articlesService->loadList($request->all());
        return \Response::JSON(array(
            'data' => view('Cms::Articles.loadList', $data)->render(),
            'perPage' => $request->limit,
        ));
    }
    /**
     * Tạo mới
     * @return view
     */
    public function create(Request $request)
    {
        $data = $this->articlesService->_create($request->all());
        return view('Cms::Articles.add', $data);
    }
    /**
     * Sửa bài viết
     * @return view
     */
    public function edit(Request $request)
    {
        $data = $this->articlesService->_edit($request->all());
        return view('Cms::Articles.add', $data);
    }
    /**
     * Cập nhật
     * @return array
     */
    public function update(Request $request) 
    {
        $data = $this->articlesService->_update($request->all());
        return $data;
    }
    /**
     * Xóa bài viết
     * @return array
     */
    public function delete(Request $request) 
    {
        $data = $this->articlesService->_delete($request->all());
        return $data;
    }
    /**
     * Xem bài viết
     * @return view
     */
    public function see(Request $request)
    {
        $data = $this->articlesService->see($request->all());
        return view('Cms::articles.approval', $data);
    }


    public function manager_comment(Request $request) {
        $arrInput = $request->input();
        $data['FK_ARTICLE'] = $arrInput['chk_item_id'];
        return view('Cms::articles.manager-comment', $data);
    }

    public function loadlist_comment(Request $request) {
        $objLib = new Library();
        $arrInput = $request->input();
        $FK_ARTICLE = $arrInput['FK_ARTICLE'];
        $ArticlesCommentModel = new \Modules\System\Cms\Models\ArticlesCommentModel();
        $objResult = $ArticlesCommentModel->_getAll($FK_ARTICLE, $arrInput['currentPage'], $arrInput['perPage'], $arrInput['search']);
        $arrData = $objResult->toArray();
        for ($i = 0; $i < sizeof($arrData['data']); $i++) {
            $arrData['data'][$i]['C_FILE_NAME'] = url('public/cms_attach_file') . '/' . $objLib->_getfolderbyfilename($arrData['data'][$i]['C_FILE_NAME']);
        }
        return \Response::JSON(array(
                    'Dataloadlist' => $arrData,
                    'pagination' => (string) $objResult->links('Cms::vendor.pagination.default'),
                    'perPage' => $arrInput['perPage'],
        ));
    }

    public function see_comment(Request $request) {
        $arrInput = $request->input();
        $id_comment = $arrInput['chk_item_id'];
        $ArticlesCommentModel = new \Modules\System\Cms\Models\ArticlesCommentModel();
        $arrSingle = $ArticlesCommentModel->where('PK_ARTICLES_COMMENT', '=', $id_comment)->get()->toArray();
        $data['arrSingle'] = $arrSingle[0];
        return view('Cms::articles.see-comment', $data);
    }

    public function approve_comment(Request $request) {
        $arrInput = $request->input();
        $ArticlesCommentModel = new \Modules\System\Cms\Models\ArticlesCommentModel();
        $id_comment = $arrInput['PK_ARTICLES_COMMENT'];
        $ArticlesCommentModel->where('PK_ARTICLES_COMMENT', '=', $id_comment)->update(['C_STATUS' => 'HIEN_THI']);
        return array('success' => true, 'message' => 'Cập nhật thành công');
    }

    public function delete_comment(Request $request) {
        $arrInput = $request->input();
        $idlist = $arrInput['listitem'];
        $sql = "DELETE T_CMS_ARTICLES_COMMENT WHERE CHARINDEX(CONVERT(varchar(50),PK_ARTICLES_COMMENT),'$idlist') >0 ";
        DB::delete($sql);
        return array('success' => true, 'message' => 'Xóa bình luận thành công');
    }

    public function deletefile(Request $request) {
        $arrInput = $request->input();
        $filename = $arrInput['filename'];
        $pkrecord = $arrInput['pkrecord'];
        $arrData['filename'] = $filename;
        $arrData['pkrecord'] = $pkrecord;
        $sql = "SELECT * FROM T_CMS_ARTICLES WHERE PK_CMS_ARTICLE = '$pkrecord'";
        $article = DB::select($sql);
        $article = array_map(function($value) {
            return (array) $value;
        }, $article);
        $explodeStr = explode(',', $article[0]['C_FILE_NAME']);
        foreach ($explodeStr as $key => $value) {
            if ($filename == $value) {
                unset($explodeStr[$key]);
            }
        }
        $file = implode(',', $explodeStr);
        DB::update("UPDATE T_CMS_ARTICLES SET C_FILE_NAME = '$file' WHERE PK_CMS_ARTICLE = '$pkrecord'");
        return \Response::JSON(array(
                    'FileData' => $arrData,
        ));
    }

    public function approval(Request $request) {
        $id = $request->itemId;
        $arrArticles = ArticlesModel::where('PK_CMS_ARTICLE', '=', $id)->select(["*", DB::raw("format(C_CREATE_DATE,'dd/MM/yyyy') as C_CREATE")])->get()->toArray();
        $ROLE = $_SESSION['user_infor']['C_ROLE'];
        if ($ROLE == 'USER') {
            $arrCategory = explode(',', trim($_SESSION['PERMISSION_CMS']['C_LIST_CATEGORY_DUYET'], ','));
            if (in_array($arrArticles[0]['FK_CATEGORY'], $arrCategory) == false) {
                return array('danger' => true, 'message' => 'Bạn không có quyền duyệt tin này');
            }
        }
        $data['action'] = 'duyet';
        $data['breadcrumb'] = 'DUYỆT BÀI VIẾT';
        $data['arrArticles'] = $arrArticles;
        return view('Cms::articles.approval', $data);
    }

    public function update_approval(Request $request) {
        $arrArticles = ArticlesModel::find($request->PK_ARTICLES);
        $arrArticles->C_STATUS_ARTICLES = 'DA_DUYET';
        $arrArticles->SAVE();
        return array('success' => true, 'message' => 'Cập nhật thành công');
    }

    public function refuse(Request $request) {
        $arrArticles = ArticlesModel::find($request->PK_ARTICLES);
        $arrArticles->C_STATUS_ARTICLES = 'TU_CHOI';
        $arrArticles->SAVE();
        return array('success' => true, 'message' => 'Cập nhật thành công');
    }

    public function check_duyet(Request $request) {
        $id = $request->id;
        $C_STATUS_ARTICLES = $request->C_STATUS_ARTICLES;
        $PK_ARTICLES = $request->PK_ARTICLES;
        $category = '';
        if ($PK_ARTICLES != '' && $PK_ARTICLES != null) {
            $arrArticles = ArticlesModel::find($PK_ARTICLES);
            $category = $arrArticles->FK_CATEGORY;
        }
        $ROLE = $_SESSION['user_infor']['C_ROLE'];
        $arrTrangThaiTinBai = db::SELECT("select code,name from T_CMS_LIST where code NOT IN ('DA_DUYET','TU_CHOI') AND FK_LISTTYPE=(SELECT PK_LISTTYPE 
        FROM T_CMS_LISTTYPE WHERE code='DM_TRANG_THAI_TIN_BAI') ORDER BY C_ORDER");
        if ($ROLE == 'USER') {
            $arrCategory = explode(',', trim($_SESSION['PERMISSION_CMS']['C_LIST_CATEGORY_DUYET'], ','));
            // dd($id);
            if (in_array($id, $arrCategory) == true || $C_STATUS_ARTICLES == 'DA_DUYET' || $C_STATUS_ARTICLES == 'TU_CHOI') {
                $arrTrangThaiTinBai = db::SELECT("select code,name from T_CMS_LIST where  FK_LISTTYPE=(SELECT PK_LISTTYPE 
                FROM T_CMS_LISTTYPE WHERE code='DM_TRANG_THAI_TIN_BAI') ORDER BY C_ORDER");
            }
            if (in_array($id, $arrCategory) == false && $category != $id && ($C_STATUS_ARTICLES == 'DA_DUYET' || $C_STATUS_ARTICLES == 'TU_CHOI')) {
                $arrTrangThaiTinBai = db::SELECT("select code,name from T_CMS_LIST where code NOT IN ('DA_DUYET','TU_CHOI') AND FK_LISTTYPE=(SELECT PK_LISTTYPE 
                FROM T_CMS_LISTTYPE WHERE code='DM_TRANG_THAI_TIN_BAI') ORDER BY C_ORDER");
            }
        }

        $html = '<div class="col-md-2"><label class="control-label required">Trạng thái tin bài</label></div>';
        foreach ($arrTrangThaiTinBai as $key => $value) {
            if (isset($C_STATUS_ARTICLES) && $C_STATUS_ARTICLES == $value->code) {
                $check = 'checked';
            } else {
                $check = '';
            }
            $html .= '<div class="col-md-2" id="' . $value->code . '">';
            $html .= '<input type="radio" ' . $check . '  value="' . $value->code . '"  name="C_STATUS_ARTICLES"> <span>' . $value->name . '</span>';
            $html .= '</div>';
        }
        return \Response::JSON($html);
    }
    public function view_detail(){

        return view('Cms::articles.docsach');
    }

}
