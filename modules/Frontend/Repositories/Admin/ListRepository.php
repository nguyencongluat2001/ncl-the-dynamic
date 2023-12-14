<?php

namespace Modules\Frontend\Repositories\Admin;

use Modules\Core\Ncl\Http\BaseRepository;
use Modules\Frontend\Models\Admin\ListModel;

/**
 * Repo đối tượng danh mục
 * 
 * @author luatnc
 */
class ListRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function model(): string
    {
        return ListModel::class;
    }

    /**
     * Lấy List theo Listtype code
     * 
     * @param string $listtypeCode
     * @return object
     */
    public function getListByListtype(string $listtypeCode, array $where = [], array $order = []): object
    {
        $list = $this->select('system_list.*')
            ->join('system_listtype', 'system_list.system_listtype_id', '=', 'system_listtype.id')
            ->where('system_listtype.code', $listtypeCode)
            ->where('system_list.status', 1);
        if (count($order) === 0) {
            $list->orderBy('system_list.order');
        } else {
            foreach ($order as $key => $value) {
                $list->orderBy($key, $value);
            }
        }
        if (count($where)) {
            foreach ($where as $key => $value) {
                if ($key === 'name') $list->where($key, 'like', '%' . $value . '%');
                else $list->where($key, $value);
            }
        }

        return $list->get();
    }
}
