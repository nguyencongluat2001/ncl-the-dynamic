<?php

namespace Modules\System\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

class AlbumRelateImageModel extends Model {

    protected $table = 'CMS_RELATE_IMAGE_ALBUM';
    protected $primaryKey = 'PK_CMS_RELATE_IMAGE_ALBUM';
    public $incrementing = false;
    public $timestamps = false;

    public function _getAll($FK_ALBUM_IMAGE, $currentPage, $perPage, $search) {
        $query = $this->query()->orderBY('C_ORDER', 'asc');
        Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
        $query->where('FK_ALBUM_IMAGE', '=', $FK_ALBUM_IMAGE);
        if ($search) {
            $query->where('C_NAME', 'LIKE', '%' . $search . '%');
        }
        return $query->paginate($perPage);
    }

}
