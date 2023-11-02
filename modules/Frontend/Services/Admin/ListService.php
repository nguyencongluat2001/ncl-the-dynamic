<?php

namespace Modules\Frontend\Services\Admin;

use Modules\Core\Efy\Http\BaseService;
use Modules\Frontend\Repositories\Admin\ListRepository;

/**
 * Xử lý đối tượng danh mục
 * 
 * @author khuongtq
 */
class ListService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function repository(): string
    {
        return ListRepository::class;
    }

    /**
     * Lấy List theo Listtype code
     * 
     * @param string $listtypeCode
     * @return object
     */
    public function getByListtypeCode(string $listtypeCode, array $where = [], array $order = []): object
    {
        return $this->repository->getListByListtype($listtypeCode, $where, $order);
    }

    /**
     * Lấy List theo Listtype code
     * 
     * @param string $listtypeCode
     * @return object
     */
    public function getAllUnits(): object
    {
        $units = $this->repository->getListByListtype('DM_DON_VI');
        $units->map(function ($u) {
            $xml            = simplexml_load_string($u->xml_data)->data_list;
            $u->unit_level  = (string)$xml->unit_level;
            $u->parent_code = (string)$xml->parent_code;
            $u->parent_name = (string)$xml->parent_name;
            $u->note_list   = (string)$xml->note_list;
            unset($u->xml_data);
        });

        return $units;
    }

    /**
     * Lấy đơn vị theo cấp đơn vị
     * 
     * @param string $level cấp đơn vị
     * @return object
     */
    public function getUnitByLevel(string $level): object
    {
        $list = $this->select('system_list.code', 'system_list.name', 'system_list.order', 'system_list.xml_data')
            ->join('system_listtype', 'system_list.system_listtype_id', '=', 'system_listtype.id')
            ->where('system_listtype.code', 'DM_DON_VI')
            ->where('system_list.status', 1)
            ->whereRaw("CAST(system_list.xml_data AS XML).value('(/root/data_list/unit_level)[1]', 'varchar(50)') = '$level'")
            ->orderByRaw("CAST(system_list.xml_data AS XML).value('(/root/data_list/parent_code)[1]', 'varchar(50)') asc")
            ->orderBy('system_list.code')->get();
        $list->map(function ($l) {
            $xml = simplexml_load_string($l->xml_data)->data_list;
            $l->unit_level = (string)$xml->unit_level;
            $l->parent_code = (string)$xml->parent_code;
            $l->parent_name = (string)$xml->parent_name;
            unset($l->xml_data);
        });

        return $list;
    }
}
