<?php
namespace Modules\System\Cms\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use DB;
use Modules\Core\Efy\Library;
use Uuid;

class CirculateModel extends Model
{
    protected $table = 'eLIB_CIRCULATE';
    protected $primaryKey = 'PK_CIRCULATE';
    public $incrementing = false;
    public $timestamps = true;
    const CREATED_AT = 'C_CREATE_AT';
    const UPDATED_AT = 'C_UPDATE_AT';
	
    public function _getAll($currentPage=1,$perPage=15,$status,$FK_READER )
    {
    	$query = $this->query();
    	Paginator::currentPageResolver(function() use ($currentPage) {
    		return $currentPage;
        });
      $query->SELECT("eLIB_CIRCULATE.*",'eLIB_BOOK.C_PATH','eLIB_PUBLICATION.C_FULL_NAME','eLIB_PUBLICATION.C_AUTHOR','eLIB_BOOK.C_CODE','eLIB_PUBLICATION.C_IMAGE_ARVARTAR',
      DB::raw('(select convert(varchar(50),C_DATE_BORROW,103)) as NGAY_MUON'),DB::raw('(select convert(varchar(50),C_DATE_APPOINTED,103)) as NGAY_HEN'),DB::raw('(select convert(varchar(50),C_DATE_GIVEBACK,103)) as NGAY_TRA')
      ,DB::raw("(select dbo.f_GetNamelist(T_eLIB_CIRCULATE.C_STATUS,'DM_TRANG_THAI_LUU_THONG') ) AS STATUS"))
      ->join('eLIB_BOOK','eLIB_BOOK.PK_BOOK','=','eLIB_CIRCULATE.FK_BOOK')
      ->join('eLIB_PUBLICATION','eLIB_BOOK.FK_PUBLICATION','=','eLIB_PUBLICATION.PK_PUBLICATION')->orderBy('eLIB_CIRCULATE.C_DATE_BORROW','DESC');
      $query->where('eLIB_CIRCULATE.FK_READER', '=',$FK_READER );
      $query->whereIn('eLIB_CIRCULATE.C_STATUS',['DANG_MUON','DA_TRA','MAT','GIA_HAN_TAI_LIEU']);
      // if($search){
    	// 		$query->whereRaw("dbo.f_GetFullNamePublication(T_eLIB_PUBLICATION.PK_PUBLICATION) like N'%$search%'")
    	// 		->orWhere('eLIB_BOOK.C_CODE', 'LIKE','%' . $search .'%')
    	// 		->orWhere('eLIB_BOOK.C_PATH', 'LIKE','%' . $search .'%');
    	// 	}
        if($status!=''){
          $query->where('eLIB_CIRCULATE.C_STATUS','=',$status);
        }

            
    		return $query->paginate($perPage);
    }   
    
}