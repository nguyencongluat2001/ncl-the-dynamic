<?php

namespace Modules\System\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

class AlbumImagesModel extends Model {

    protected $table = 'CMS_ALBUM_IMAGES';
    protected $primaryKey = 'PK_CMS_ALBUM_IMAGES';
    public $incrementing = false;
    public $timestamps = false;

    public function _getAll($currentPage, $perPage, $search) {
        $query = $this->query()->orderBY('C_ORDER', 'asc');
        Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
        $query->where('C_OWNER_CODE', '=', $_SESSION['OWNER_CODE']);
        if ($search) {
            $query->where('C_NAME', 'LIKE', '%' . $search . '%');
        }
        return $query->paginate($perPage);
    }

}
