<?php

namespace Modules\System\Listtype\Repositories;

use Illuminate\Pagination\Paginator;
use Modules\System\Listtype\Models\ListModel;

class ListRepository
{
    private $listModel;

    public function __construct(ListModel $l)
    {
        $this->listModel = $l;
    }

    /**
     * Lấy dữ liệu
     * 
     * @param int $listtype
     * @param int $currentPage
     * @param int $perPage
     * @param string $search
     * @param string $unit
     * @return object|null|false
     */
    public function getAll(int $listtype, int $currentPage = 1, int $perPage = 15, string $search = '', string $unit = ''): object|null|false
    {
        if (!$listtype) return false;
        $list = $this->listModel::where('system_listtype_id', $listtype)->orderBy('order');
        Paginator::currentPageResolver(fn () => $currentPage);
        if ($unit && $unit !== '') {
            $list->where('owner_code_list', 'LIKE', '%' . $unit . '%');
        }
        if ($search) {
            $list->where('code', 'LIKE', '%' . $search . '%')
                ->orWhere('name', 'LIKE', '%' . $search . '%');
        }

        return $list->paginate($perPage);
    }

    /**
     * Cập nhật hoặc thêm mới
     * 
     * @param string $id
     * @param array $params
     * @return bool
     */
    public function update(string $id, array $params): bool
    {
        if ($id) {
            $params['updated_at'] = date('Y-m-d h:i:s');
            return $this->listModel->find($id)->update($params);
        } else {
            $timestamps = date('Y-m-d h:i:s');
            $list = new $this->listModel;
            $list->created_at = $timestamps;
            $list->updated_at = $timestamps;
            $list->code = $params['code'];
            $list->system_listtype_id = $params['system_listtype_id'];
            $list->name = $params['name'];
            $list->order = $params['order'];
            $list->xml_data = $params['xml_data'];
            $list->status = $params['status'];
            $list->owner_code_list = $params['owner_code_list'];

            return $list->save();
        }
    }

    /**
     * Xóa
     * 
     * @param string $listitem
     * @return void
     */
    public function delete($listitem): void
    {
        $arrListitem = explode(',', $listitem);
        $this->listModel->whereIn('id', $arrListitem)->delete();
    }
}
