<?php

namespace Modules\System\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use DB;

class DocumentsModel extends Model {

    protected $table = 'CMS_DOCUMENT';
    protected $primaryKey = 'PK_CMS_DOCUMENT';
    public $incrementing = true;
    public $timestamps = false;

    public function _getAll($currentPage, $perPage, $search, $fromdate, $todate, $documents_type, $dateeffect) {
        $query = $this->query()->orderBY('C_CREATE_DATE', 'desc');
        Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
        $query->where('C_OWNER_CODE', '=', $_SESSION['OWNER_CODE']);
        $query->select(['*', DB::raw('(SELECT TOP 1 C_NAME from T_CMS_LIST where T_CMS_LIST.C_CODE = T_CMS_DOCUMENT.C_DOCTYPE) as C_DOCTYPE_NAME'), DB::raw("format(C_CREATE_DATE,'dd/MM/yyyy HH:mm:ss') as C_CREATE")]);
        if ($search) {
            $query->where(function($query)use ($search) {
                $query->where('C_SUBJECT', 'like', '%' . $search . '%')
                        ->orWhere('C_SYMBOL', 'like', '%' . $search . '%')
                        ->orWhere('C_SIGNER', 'like', '%' . $search . '%');
            });
        }
        if ($fromdate) {
            $query->whereRaw("datediff(day,C_DATE_PUBLIC,'$fromdate')<=0");
        }
        if ($todate) {
            $query->whereRaw("datediff(day,C_DATE_PUBLIC,'$todate')>=0");
        }
        if ($dateeffect) {
            $query->whereRaw("datediff(day,C_DATE_EFFECT,'$dateeffect')=0");
        }
        if ($documents_type) {
            $query->where('C_DOCTYPE', '=', $documents_type);
        }
        return $query->paginate($perPage);
    }

    public function _getAllFrontEnd($ownercode, $currentPage, $perPage, $search, $fromdate, $todate, $documents_type) {
        $query = $this->query()->orderBY('C_CREATE_DATE', 'desc');
        Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
        $query->where('C_OWNER_CODE', '=', $ownercode);
        $query->where('C_STATUS', '=', 'HOAT_DONG');
        $query->select(['*', DB::raw('(SELECT TOP 1 C_NAME from T_CMS_LIST where T_CMS_LIST.C_CODE = T_CMS_DOCUMENT.C_DOCTYPE) as C_DOCTYPE_NAME'), DB::raw("format(C_CREATE_DATE,'dd/MM/yyyy HH:mm:ss') as C_CREATE")]);
        if ($search) {
            $query->where(function($query)use ($search) {
                $query->where('C_SUBJECT', 'like', '%' . $search . '%')
                        ->orWhere('C_SYMBOL', 'like', '%' . $search . '%')
                        ->orWhere('C_SIGNER', 'like', '%' . $search . '%');
            });
        }
        if ($fromdate) {
            $query->whereRaw("datediff(day,C_CREATE_DATE,'$fromdate')<=0");
        }
        if ($todate) {
            $query->whereRaw("datediff(day,C_CREATE_DATE,'$todate')>=0");
        }
        if ($documents_type) {
            $query->where('C_DOCTYPE', '=', $documents_type);
        }
        return $query->paginate($perPage);
    }

    public function _getAllFrontEndOrderByDatePublic($ownercode, $currentPage, $perPage, $search, $fromdate, $todate, $documents_type) {
        $query = $this->query()->orderBY('C_DATE_PUBLIC', 'desc');
        Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
        $query->where('C_OWNER_CODE', '=', $ownercode);
        $query->where('C_STATUS', '=', 'HOAT_DONG');
        $query->select(['*', DB::raw('(SELECT TOP 1 C_NAME from T_CMS_LIST where T_CMS_LIST.C_CODE = T_CMS_DOCUMENT.C_DOCTYPE) as C_DOCTYPE_NAME'), DB::raw("format(C_CREATE_DATE,'dd/MM/yyyy HH:mm:ss') as C_CREATE")]);
        if ($search) {
            $query->where(function($query)use ($search) {
                $query->where('C_SUBJECT', 'like', '%' . $search . '%')
                    ->orWhere('C_SYMBOL', 'like', '%' . $search . '%')
                    ->orWhere('C_SIGNER', 'like', '%' . $search . '%');
            });
        }
        if ($fromdate) {
            $query->whereRaw("datediff(day,C_CREATE_DATE,'$fromdate')<=0");
        }
        if ($todate) {
            $query->whereRaw("datediff(day,C_CREATE_DATE,'$todate')>=0");
        }
        if ($documents_type) {
            $query->where('C_DOCTYPE', '=', $documents_type);
        }
        return $query->paginate($perPage);
    }

    public function _getAllFrontEndQDTTHC($ownercode, $currentPage, $perPage, $search, $fromdate, $todate, $documents_type) {
        $query = $this->query()->orderBY('C_DATE_PUBLIC', 'desc');
        Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
        $query->where('C_OWNER_CODE', '=', $ownercode);
        $query->where('C_STATUS', '=', 'HOAT_DONG');
        $query->where('C_IS_DECISION_PROCEDURE', '=', '1');
        $query->select(['*', DB::raw('(SELECT TOP 1 C_NAME from T_CMS_LIST where T_CMS_LIST.C_CODE = T_CMS_DOCUMENT.C_DOCTYPE) as C_DOCTYPE_NAME'), DB::raw("format(C_CREATE_DATE,'dd/MM/yyyy HH:mm:ss') as C_CREATE")]);
        if ($search) {
            $query->where(function($query)use ($search) {
                $query->where('C_SUBJECT', 'like', '%' . $search . '%')
                    ->orWhere('C_SYMBOL', 'like', '%' . $search . '%')
                    ->orWhere('C_SIGNER', 'like', '%' . $search . '%');
            });
        }
        if ($fromdate) {
            $query->whereRaw("datediff(day,C_CREATE_DATE,'$fromdate')<=0");
        }
        if ($todate) {
            $query->whereRaw("datediff(day,C_CREATE_DATE,'$todate')>=0");
        }
        if ($documents_type) {
            $query->where('C_DOCTYPE', '=', $documents_type);
        }
        return $query->paginate($perPage);
    }
}
