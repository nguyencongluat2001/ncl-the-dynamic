<?php

namespace Modules\System\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Pagination\Paginator;
use Modules\System\Listtype\Models\ListModel;

class QuestionsModel extends Model {
    protected $table = 'CMS_QUESTIONS';
    protected $primaryKey = 'PK_CMS_QUESTION';
    public $incrementing = false;
    public $timestamps = false;

    public function _getAll($currentPage, $perPage, $search, $questionType, $category, $statusQuestion) {
        $query = $this->query()->orderBY(DB::raw("(select C_ORDER from T_CMS_LIST where C_CODE=T_CMS_QUESTIONS.C_STATUS_QUESTION and FK_LISTTYPE=(select PK_LISTTYPE  from T_CMS_LISTTYPE where C_CODE='DM_TRANG_THAI_CAU_HOI'))"),'ASC');
        $query->select('*',DB::raw("(select count(*) from T_CMS_ANSWERS where FK_QUESTION=T_CMS_QUESTIONS.PK_CMS_QUESTION) AS TRA_LOI"));
        Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });

        $query->where('C_OWNER_CODE', '=', $_SESSION['OWNER_CODE']);
        if ($search) {
            $query->where(function($query)use ($search) {
                $query->where('C_CONTENT', 'like', '%' . $search . '%');
            });
        }
        if ($statusQuestion) {
            $query->where('C_STATUS_QUESTION', '=', $statusQuestion);
        }
        if ($questionType) {
            $query->where('C_QUESTION_TYPE', '=', $questionType);
        }
        if ($category) {
            $query->where('C_CATEGORY', '=', $category);
        }
        return $query->paginate($perPage);
    }

    public function _getAllFrontEnd($search, $category, $ownercode, $currentPage, $perPage, $questionType) {
        $query = $this->query()->orderBY('C_CONTENT', 'asc');
        $query->where('C_STATUS_QUESTION', '=', 'DA_DUYET');
        $query->where('C_OWNER_CODE', '=', $ownercode);
        Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
        if ($questionType) {
            $query->where('C_QUESTION_TYPE', '=', $questionType);
        }
        if ($search) {
            $query->where('C_CONTENT', 'LIKE', '%' . $search . '%');
        }
        if ($category) {
            $query->where('C_CATEGORY', '=', $category);
        }
        return $query->paginate($perPage);
    }

    public function answers()
    {
        return $this->hasMany('Modules\System\Cms\Models\AnswersModel', 'FK_QUESTION');
    }
}
