<?php

namespace Modules\System\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use DB;

class ArticlesCommentModel extends Model {

    protected $table = 'CMS_ARTICLES_COMMENT';
    protected $primaryKey = 'PK_ARTICLES_COMMENT';
    public $incrementing = false;
    public $timestamps = false;

    public function _getAll($fkarticle, $currentPage, $perPage, $search) {
        $query = $this->query()->orderBY('C_ORDER');
        Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
        $query->where('FK_ARTICLE', '=', $fkarticle);
        $query->select(['*', DB::raw("format(getdate(),'dd/MM/yyyy') as C_CREATE")]);
        if ($search) {
            $query->where('C_NAME_SENDER', 'LIKE', '%' . $search . '%');
        }
        return $query->paginate($perPage);
    }

}
