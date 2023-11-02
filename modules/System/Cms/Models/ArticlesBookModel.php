<?php

namespace Modules\System\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use DB;

class ArticlesBookModel extends Model {

    protected $table = 'CMS_ARTICLES_BOOK';
    protected $primaryKey = 'PK_CMS_ARTICLE_BOOK';
    public $incrementing = false;
    public $timestamps = false;

    public function _getAll($currentPage, $perPage, $search, $fromdate, $todate) {
        $query = $this->query()->orderBY('C_CREATE_DATE', 'desc');
        Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
        $query->where('C_OWNER_CODE', '=', $_SESSION['OWNER_CODE']);
        $query->select(['*', DB::raw("format(C_CREATE_DATE,'dd/MM/yyyy') as C_CREATE")]);
        if ($search) {
            $query->where(function($query)use ($search) {
                $query->where('C_SUBJECT', 'like', '%' . $search . '%')
                        ->orWhere('C_AUTHOR', 'like', '%' . $search . '%')->orWhere('C_TITLE', 'like', '%' . $search . '%');
            });
//            $query->where('C_SUBJECT', 'LIKE', '%' . $search . '%');
        }
        if ($fromdate) {
            $query->whereRaw("datediff(day,C_CREATE_DATE,'$fromdate')<=0");
        }
        if ($todate) {
            $query->whereRaw("datediff(day,C_CREATE_DATE,'$todate')>=0");
        }
        return $query->paginate($perPage);
    }

    public function _getAllFrontEnd($fk_category, $ownercode, $currentPage, $perPage, $search = null) {
        $query = $this->query()->orderBY('C_CREATE_DATE', 'desc');
        $query->where('FK_CATEGORY', '=', $fk_category);
        $query->where('C_STATUS_ARTICLES', '=', 'DA_DUYET');
        $query->where('C_OWNER_CODE', '=', $ownercode);
        $query->select(['*', DB::raw('(SELECT TOP 1 C_NAME from T_CMS_CATEGORIES where T_CMS_CATEGORIES.PK_CATEGORIES = T_CMS_ARTICLES.FK_CATEGORY) as CATEGORY_NAME'), DB::raw("format(C_CREATE_DATE,'dd/MM/yyyy HH:mm:ss') as C_CREATE")]);
        Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
        if ($search) {
            $query->where('C_NAME', 'LIKE', '%' . $search . '%');
        }
        return $query->paginate($perPage);
    }

    public function _getAllFrontEndSearch($text_search, $ownercode, $currentPage, $perPage) {
        $query = $this->query()->orderBY('C_CREATE_DATE', 'desc');
        $query->where('C_STATUS_ARTICLES', '=', 'DA_DUYET');
        $query->where('C_OWNER_CODE', '=', $ownercode);
        Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
        if ($text_search) {
            $query->where(function($query)use ($text_search) {
                $query->where('C_SUBJECT', 'like', '%' . $text_search . '%')
                        ->orWhere('C_TITLE', 'like', '%' . $text_search . '%')->orWhere('C_CONTENT', 'like', '%' . $text_search . '%')->orWhere('C_AUTHOR', 'like', '%' . $text_search . '%');
            });
        }
        return $query->paginate($perPage);
    }

    public function _getAllFrontEndDisplayOrder($fk_category, $ownercode, $currentPage, $perPage, $search = null) {
        $query = $this->query()->orderBy('C_ORDER', 'asc');
        $query->where('FK_CATEGORY', '=', $fk_category);
        $query->where('C_STATUS_ARTICLES', '=', 'DA_DUYET');
        $query->where('C_OWNER_CODE', '=', $ownercode);
        $query->select(['*', DB::raw('(SELECT TOP 1 C_NAME from T_CMS_CATEGORIES where T_CMS_CATEGORIES.PK_CATEGORIES = T_CMS_ARTICLES.FK_CATEGORY) as CATEGORY_NAME'), DB::raw("format(C_CREATE_DATE,'dd/MM/yyyy HH:mm:ss') as C_CREATE")]);
        Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
        if ($search) {
            $query->where('C_NAME', 'LIKE', '%' . $search . '%');
        }
        return $query->paginate($perPage);
    }

    public function _getAllExport($fromdate, $todate, $category, $articles_type) {
        $list_category = implode(',', $_SESSION['C_LIST_CATEGORY']);
        $query = $this->query()->orderBY('C_CREATE_DATE', 'desc');
        $query->where('C_OWNER_CODE', '=', $_SESSION['OWNER_CODE']);
        $query->select(['*', DB::raw('(SELECT TOP 1 C_NAME from T_CMS_CATEGORIES where T_CMS_CATEGORIES.PK_CATEGORIES = T_CMS_ARTICLES.FK_CATEGORY) as CATEGORY_NAME'), DB::raw("format(C_CREATE_DATE,'dd/MM/yyyy') as C_CREATE")]);
        if ($_SESSION['role'] == 'USER') {
            $query->whereRaw("CHARINDEX(CONVERT(VARCHAR(50),FK_CATEGORY),'$list_category')>0");
        }
        if ($fromdate) {
            $query->whereRaw("datediff(day,C_CREATE_DATE,'$fromdate')<=0");
        }
        if ($todate) {
            $query->whereRaw("datediff(day,C_CREATE_DATE,'$todate')>=0");
        }
        if ($category) {
            $query->where('FK_CATEGORY', '=', $category);
        }
        return $query->get();
    }

}
