<?php

namespace Modules\System\Helpers;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginationHelper
{
    /**
     * Chuyển một mảng về dạng phân trang
     * @param $item Mảng cần chuyển đổi
     * @param $perPage Số bản ghi trên một trang
     * @param $page Số trang
     */
    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
?>