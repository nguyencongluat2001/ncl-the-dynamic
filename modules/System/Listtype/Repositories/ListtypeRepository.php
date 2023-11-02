<?php

namespace Modules\System\Listtype\Repositories;

use Illuminate\Pagination\Paginator;
use Modules\System\Listtype\Models\ListtypeModel;

class ListtypeRepository
{
    private $listtypeModel;

    public function __construct(ListtypeModel $l)
    {
        $this->listtypeModel = $l;
    }

    /**
     * Lấy dữ liệu
     * 
     * @param int $currentPage
     * @param int $perPage
     * @param object|null
     */
    public function getAll(int $currentPage, int $perPage, string $search = '', string $unit = ''): object|null
    {
        $query = $this->listtypeModel->query();
        Paginator::currentPageResolver(fn () => $currentPage);
        if ($unit && $unit !== '') {
            $query->where('owner_code_list', 'LIKE', '%' . $unit . '%');
        }
        if ($search && trim($search) !== '') {
            $query->where('code', 'LIKE', '%' . $search . '%')->orWhere('name', 'LIKE', '%' . $search . '%');
        }

        return $query->paginate($perPage);
    }

    /**
     * Cập nhật hoặc thêm mới danh mục
     * 
     * @param string $id
     * @param array $params
     * @return bool
     */
    public function update(string $id, array $params): bool
    {
        if ($id !== '') {
            return $this->listtypeModel->find($id)->update($params);
        } else {
            return $this->listtypeModel->insert($params);
        }
    }

    /**
     * Xóa danh mục
     * 
     * @param string $listitem
     * @return void
     */
    public function delete(string $listitem): void
    {
        $arrListitem = explode(',', $listitem);
        $this->listtypeModel->whereIn('id', $arrListitem)->delete();
    }
}
