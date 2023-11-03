<?php

namespace Modules\System\Cms\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Core\Ncl\Library;
use Modules\System\Cms\Models\AnswersModel;
use Modules\System\Cms\Models\QuestionsModel;
use Modules\System\Listtype\Helpers\ListtypeHelper;
use DB;
use Modules\System\Listtype\Models\ListModel;
use Uuid;

class QuestionsController extends Controller {

    public function __construct() {
        $check = Library::checkPermissionController('CmsQuestionsController');
        if (!$check) {
            die('Bạn không có quyền vào chức năng này');
        }
    }

    public function index() {
        $obj = new Library();
        $result = array();
        $result = $obj->_getAllFileJavaScriptCssArray('js', 'system/Cms/JS_Questions.js', ',', $result,'JS_Questions.min.js');
        $result = $obj->_getAllFileJavaScriptCssArray('js', 'assets/ckeditor/ckeditor.js', ',', $result);
        $data['stringJsCss'] = json_encode($result);
        $listModel = new ListModel();
        $arrCategory = $listModel->_getAllbyCode('DM_LINH_VUC', false, ['C_CODE', 'C_NAME']);
        $arrQuestionType = $listModel->_getAllbyCode('DM_LOAI_CAU_HOI', false, ['C_CODE', 'C_NAME']);
        $arrStatusQuestion = $listModel->_getAllbyCode('DM_TRANG_THAI_CAU_HOI', false, ['C_CODE', 'C_NAME']);
        $data['arrCategory'] = $arrCategory;
        $data['arrQuestionType'] = $arrQuestionType;
        $data['arrStatusQuestion'] = $arrStatusQuestion;
        return view('Cms::questions.index', $data);
    }

    public function loadlist(Request $request) {
        $arrInput = $request->input();
        $search = $arrInput['search'];
        $questionType = $arrInput['questions_type'];
        $category = $arrInput['category'];
        $statusQuestion = $arrInput['status_question'];
        $objQuestion = new QuestionsModel();
        $objResult = $objQuestion->_getAll($arrInput['currentPage'], $arrInput['perPage'], $search, $questionType, $category, $statusQuestion);
        $listModel = new ListModel();
        $arrCategory = $listModel->_getAllbyCode('DM_LINH_VUC', false, ['C_CODE', 'C_NAME']);
        $arrQuestionType = $listModel->_getAllbyCode('DM_LOAI_CAU_HOI', false, ['C_CODE', 'C_NAME']);
        $arrStatusQuestion = $listModel->_getAllbyCode('DM_TRANG_THAI_CAU_HOI', false, ['C_CODE', 'C_NAME']);
        foreach ($objResult as $key => $value) {
            $objResult[$key]['C_CATEGORY_NAME'] = '';
            foreach ($arrCategory as $category) {
                if ($category['C_CODE'] == $value['C_CATEGORY']) {
                    $objResult[$key]['C_CATEGORY_NAME'] = $category['C_NAME'];
                }
            }
            $objResult[$key]['C_TYPE'] = '';
            foreach ($arrQuestionType as $type) {
                if ($type['C_CODE'] == $value['C_QUESTION_TYPE']) {
                    $objResult[$key]['C_TYPE'] = $type['C_NAME'];
                }
            }
            $objResult[$key]['C_TYPE'] = '';
            foreach ($arrStatusQuestion as $type) {
                if ($type['C_CODE'] == $value['C_STATUS_QUESTION']) {
                    $objResult[$key]['C_STATUS_QUESTION'] = $type['C_NAME'];
                }
            }
        }
        return \Response::JSON(array(
            'Dataloadlist' => $objResult,
            'pagination' => (string) $objResult->links('Cms::vendor.pagination.default'),
            'perPage' => $arrInput['perPage'],
        ));
    }

    public function questions_add() {
        $data = [];
        $listModel = new ListModel();
        $arrCategory = $listModel->_getAllbyCode('DM_LINH_VUC', false, ['C_CODE', 'C_NAME']);
        $arrQuestionType = $listModel->_getAllbyCode('DM_LOAI_CAU_HOI', false, ['C_CODE', 'C_NAME']);
        $arrStatusQuestion = $listModel->_getAllbyCode('DM_TRANG_THAI_CAU_HOI', false, ['C_CODE', 'C_NAME']);
        $data['arrCategory'] = $arrCategory;
        $data['arrQuestionType'] = $arrQuestionType;
        $data['arrStatusQuestion'] = $arrStatusQuestion;
        return view('Cms::Questions.add', $data);
    }

    public function questions_update(Request $request) {
        $arrInput = $request->input();
        $data = $arrInput['data'];
        $arrDataForm = $this->queryToArray($data);
        $status = isset($arrDataForm['C_STATUS']) ? 'HOAT_DONG' : 'KHONG_HOAT_DONG';
        if (isset($arrDataForm['PK_QUESTIONS']) && $arrDataForm['PK_QUESTIONS'] != '') {
            $id = $arrDataForm['PK_QUESTIONS'];
            $questionsModel = QuestionsModel::find($id);
        } else {
            $id = Uuid::generate();
            $questionsModel = new QuestionsModel();
        }

        $questionsModel->PK_CMS_QUESTION = $id;
        $questionsModel->C_CATEGORY = $arrDataForm['C_CATEGORY'];
        $questionsModel->C_QUESTION_TYPE = $arrDataForm['C_QUESTION_TYPE'];
        $questionsModel->C_CONTENT = $arrDataForm['C_CONTENT'];
        $questionsModel->C_STATUS_QUESTION = $arrDataForm['C_STATUS_QUESTION'];
        $questionsModel->C_STATUS = $status;
        $questionsModel->C_OWNER_CODE = $_SESSION['OWNER_CODE'];
        $questionsModel->save();
        return array('success' => true, 'message' => 'Cập nhật thành công', 'parent_id' => $request->parent_id);
    }

    public function questions_edit(Request $request) {
        $data = [];
        $arrInput = $request->input();
        $listModel = new ListModel();
        $arrCategory = $listModel->_getAllbyCode('DM_LINH_VUC', false, ['C_CODE', 'C_NAME']);
        $arrQuestionType = $listModel->_getAllbyCode('DM_LOAI_CAU_HOI', false, ['C_CODE', 'C_NAME']);
        $arrStatusQuestion = $listModel->_getAllbyCode('DM_TRANG_THAI_CAU_HOI', false, ['C_CODE', 'C_NAME']);
        $data['arrCategory'] = $arrCategory;
        $data['arrQuestionType'] = $arrQuestionType;
        $data['arrStatusQuestion'] = $arrStatusQuestion;
        $objQuestionsModel = new QuestionsModel();
        $arrSingle = $objQuestionsModel->where('PK_CMS_QUESTION', $arrInput['chk_item_id'])->get();
        $data['arrSingle'] = $arrSingle[0];
        return view('Cms::Questions.add', $data);
    }

    public function questions_delete(Request $request) {
        $arrInput = $request->input();
        $idlist = $arrInput['listitem'];
        $arrListItem = explode(',', $idlist);
        foreach ($arrListItem as $item) {
            $questionModel = new QuestionsModel();
            $question = $questionModel::find($item);
            $question->delete();
            $fkQuestion = $item;
            $answerSql = "DELETE FROM T_CMS_ANSWERS WHERE FK_QUESTION IN('$fkQuestion') ";
            DB::delete($answerSql);
        }
        return array('success' => true, 'message' => 'Cập nhật thành công', 'parent_id' => $request->parent_id);
    }

    public function answer_question(Request $request) {
        $arrInput = $request->input();
        $data['FK_QUESTION'] = $arrInput['chk_item_id'];
        $listModel = new ListModel();
        $arrCategory = $listModel->_getAllbyCode('DM_LINH_VUC', false, ['C_CODE', 'C_NAME']);
        $arrQuestionType = $listModel->_getAllbyCode('DM_LOAI_CAU_HOI', false, ['C_CODE', 'C_NAME']);
        $arrStatusQuestion = $listModel->_getAllbyCode('DM_TRANG_THAI_CAU_HOI', false, ['C_CODE', 'C_NAME']);
        $data['arrCategory'] = $arrCategory;
        $data['arrQuestionType'] = $arrQuestionType;
        $data['arrStatusQuestion'] = $arrStatusQuestion;
        $objQuestionsModel = new QuestionsModel();
        $arrSingle = $objQuestionsModel->where('PK_CMS_QUESTION', $arrInput['chk_item_id'])->get();
        $data['arrSingle'] = $arrSingle[0];
        return view('Cms::Questions.answer-question', $data);
    }

    public function reply(Request $request) {
        $arrInput = $request->input();
        $data = $arrInput['data'];
        $objLibrary = new Library();
        $arrDataForm = $this->queryToArray($data);
        $questionId = $arrDataForm['FK_QUESTION'];

        $answerId = Uuid::generate();
        $answersModel = new AnswersModel();
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
            $answersModel->C_FILE_NAME = trim($answersModel->C_FILE_NAME .= trim($fileattach, ','));
        }
        $answersModel->PK_CMS_ANSWER = $answerId;
        $answersModel->FK_QUESTION = $questionId;
        $answersModel->C_CONTENT = $arrInput['C_ANSWER_CONTENT'];
        $answersModel->C_OWNER_CODE = $_SESSION['OWNER_CODE'];
        $answersModel->save();
        return array('success' => true, 'message' => 'Gửi câu trả lời thành công', 'parent_id' => $request->parent_id);
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
}
