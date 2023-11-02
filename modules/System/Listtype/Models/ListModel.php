<?php

namespace Modules\System\Listtype\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\System\Listtype\Models\ListtypeModel;
use Cache;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListModel extends Model
{

    protected $table = 'system_list';

    protected $fillable = [
        'system_listtype_id',
        'code',
        'name',
        'order',
        'status',
        'owner_code_list',
        'xml_data',
        'created_at',
        'updated_at',
    ];

    /**
     * Quan hệ N-1 với listtype
     */
    public function listtype(): BelongsTo
    {
        return $this->belongsTo(ListtypeModel::class, 'system_listtype_id', 'id');
    }

    public function _getAllbyCode($code, $typeJson = false, $arrcolumn)
    {

        $ListtypeModel = new ListtypeModel();
        $result = $ListtypeModel->where('code', $code)->get()->toArray();
        $listtype = $result[0]['id'];
        $listmodel = ListtypeModel::find($listtype)->ListModel();
        if ($typeJson) {
            $result = $listmodel->select($arrcolumn)->get()->toJson();
        } else {
            $result = $listmodel->select($arrcolumn)->get()->toArray();
        }
        return $result;
    }

    public function _getAllbyCodeAStatus($code, $typeJson = false, $arrcolumn, $isDB = false)
    {
        $data = array();
        //if (!Cache::has($code) || $isDB) {
        if (true) {
            $ListtypeModel = new ListtypeModel();
            $result = $ListtypeModel->where('code', $code)->get()->toArray();

            if ($result) {
                $listtype = $result[0]['id'];
                $listmodel = ListtypeModel::find($listtype)->list();

                $result = $listmodel->select($arrcolumn)->Where('status', 1)->get();

                // $value = Cache::get('DM_DON_VI_TRIEN_KHAI');
                // var_dump($value);exit;
                $count = sizeof($result);
                for ($i = 0; $i < $count; $i++) {
                    $temp = array();
                    $temp['code'] = $result[$i]['code'];
                    $temp['name'] = $result[$i]['name'];
                    $temp['owner_code_list'] = $result[$i]['owner_code_list'];
                    if ($result[$i]['xml_data'] != '') {
                        $objXml = simplexml_load_string($result[$i]['xml_data']);
                        $datalist = (array) $objXml->data_list;
                        foreach ($datalist as $key => $value) {
                            $temp[(string) $key] = (string) $value;
                        }
                    }
                    $data[] = $temp;
                }
                //luu cache
                Cache::forever($code, $data);
                if ($typeJson) {
                    $data = json_encode($data);
                }
            }
        } else {
            $data = Cache::get($code);
        }

        return $data;
    }

    public function _getAllbyCodeAStatusOrderByName($code, $typeJson = false, $arrcolumn, $isDB = false)
    {
        $data = array();
        //if (!Cache::has($code) || $isDB) {
        if (true) {
            $ListtypeModel = new ListtypeModel();
            $result = $ListtypeModel->where('code', $code)->get()->toArray();
            if ($result) {
                $listtype = $result[0]['id'];
                $listmodel = ListtypeModel::find($listtype)->ListModel();

                $result = $listmodel->select($arrcolumn)->Where('status', 1)->orderBy('name', 'ASC')->get();

                // $value = Cache::get('DM_DON_VI_TRIEN_KHAI');
                // var_dump($value);exit;
                $count = sizeof($result);
                for ($i = 0; $i < $count; $i++) {
                    $temp = array();
                    $temp['code'] = $result[$i]['code'];
                    $temp['name'] = $result[$i]['name'];
                    $temp['owner_code_list'] = $result[$i]['owner_code_list'];
                    if ($result[$i]['xml_data'] != '') {
                        $objXml = simplexml_load_string($result[$i]['xml_data']);
                        $datalist = (array) $objXml->data_list;
                        foreach ($datalist as $key => $value) {
                            $temp[(string) $key] = (string) $value;
                        }
                    }
                    $data[] = $temp;
                }
                //luu cache
                Cache::forever($code, $data);
                if ($typeJson) {
                    $data = json_encode($data);
                }
            }
        } else {
            $data = Cache::get($code);
        }

        return $data;
    }

    public function _getSinglebyCode($code, $value, $typeJson = false)
    {

        $ListtypeModel = new ListtypeModel();
        $result = $ListtypeModel->where('code', $code)->get()->toArray();
        $listtype = $result[0]['id'];
        $listmodel = ListtypeModel::find($listtype)->ListModel();
        if ($typeJson) {
            $result = $listmodel->where('code', $value)->get()->toJson();
        } else {
            $result = $listmodel->where('code', $value)->get()->toArray();
        }
        return $result;
    }

    public function _getSingle($id, $typeJson = false)
    {
        if ($typeJson) {
            return $this->where('id', $id)->first()->toJson();
        } else {
            return $this->where('id', $id)->first()->toArray();
        }
    }
}
