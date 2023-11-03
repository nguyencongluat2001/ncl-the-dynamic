<?php

namespace Modules\Api\Services\Admin;

use Modules\Api\Repositories\Admin\ListRepository;
use Modules\Core\Ncl\Http\BaseService;

class ListService extends BaseService
{

    protected $listtypeService;

    public function __construct(
        ListTypeService $listtypeService
    ) {
        parent::__construct();
        $this->listtypeService = $listtypeService;
    }

    public function repository()
    {
        return ListRepository::class;
    }

    /**
     * Lấy danh sách danh mục theo mã danh mục
     * 
     * @param string $listtypeCode Mã danh mục (vd: DM_NHOM_TTHC)
     * @return object $return Object các list thuộc danh mục
     */
    public function getByListtypeCode($listtypeCode)
    {
        $listtype = $this->listtypeService->where('code', $listtypeCode)->first();
        return $this->repository->where('system_listtype_id', $listtype->id)->get();
    }

    /**
     * Lấy danh sách đối tượng theo mã danh mục và alias name
     * 
     * @param string $listtypeCode Mã danh mục
     * @param string $key Cột được lấy làm key
     * @param string $keyAlias Tên thay thế của cột key
     * @param string $value Cột được lấy làm value
     * @param string $valueAlias tên thay thế của cột value
     * @return object
     */
    public function getAliasByListtypeCode(string $listtypeCode, string $key, string $keyAlias, string $value, string $valueAlias): object
    {
        $listtype = $this->listtypeService->where('code', $listtypeCode)->first();
        return $this->repository->where('system_listtype_id', $listtype->id)->select([
            $key . ' as ' . $keyAlias,
            $value . ' as ' . $valueAlias
        ])->get();
    }

    /**
     * Lấy lĩnh vực theo owner_code
     * 
     * @param Object|Collection $unit Thông tin đơn vị
     * @param Object|Collection $unitParent Thông tin đơn vị cha
     * @return Object|Collection $return Danh sách lĩnh vực
     */
    public function getCate($unit, $unitParent)
    {
        $query = $this->select('system_list.code', 'system_list.name')
            ->join('ecs_recordtype as b', 'system_list.code', '=', 'b.cate')
            ->where('b.owner_code', 'like', '%' . $unit->owner_code . '%');
        if ($unit->type_group == 'PHUONG_XA') {
            $query->where('b.type_unit', 'like', '%PHUONG_XA%');
        } else {
            if ($unitParent->type_group == 'QUAN_HUYEN') {
                $query->where('b.type_unit', 'like', '%QUAN_HUYEN%');
            } else {
                $query->where('b.type_unit', 'like', '%SO_NGANH%');
            }
        }
        return $query->distinct()
            ->orderby('system_list.name')
            ->get();
    }
}
