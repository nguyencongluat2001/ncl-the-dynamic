<?php

namespace Modules\Core\Ncl\Tree;

class TreeGroupObject
{

    protected $objectService;
    protected $rootId;
    protected $parentName;
    protected $treeData;
    private $column = [
        "id", "name", "code", "parentName", "users_id", "type", "children"
    ];

    public function setObjectService($objectService)
    {
        $this->objectService = $objectService;
    }

    public function setRootId($rootId)
    {
        $this->rootId = $rootId;
    }

    public function get()
    {
        $objectRoot =  $this->objectService->find($this->rootId);
        $id = $objectRoot->id;
        $name = $objectRoot->name;
        $code = $objectRoot->code;
        $users_id = $objectRoot->users_id;
        $parentName = $objectRoot->name;
        $type = 'unit';
        $children = $this->getChildByParent(compact("id", "name"));
        return compact($this->column);
    }

    private function checkChild($idParent){
        $count = $this->objectService->where('object_group_id', $idParent)->count();
        if($count > 0){
            return true;
        }
        return false;
    }

    private function getChild($idParent){
        $result = $this->objectService->select("id","users_id","name","code")->where("object_group_id",$idParent )->orderBy('order', 'asc')->get();
         return $result ;
    }

    private function checkStaffByGroup($idParent){
        $count = $this->objectService->objectPersonalService->where('object_group_id', $idParent)->count();
        if($count > 0){
            return true;
        }
        return false;
    }

    private function getStaffByGroup($idParent){
        $result = $this->objectService->objectPersonalService->where("object_group_id",$idParent )->orderBy('order', 'asc')->get();
         return $result;
    }

    public function getChildByParent($data)
    {
        $result = array();
        $idParent = $data['id'];
        $parentName = $data['name'];
        $i = 0;
        // Kiểm tra còn phòng ban hay không
        if ($this->checkChild($idParent)) {
            // Lấy danh sách phòng ban con
            $ChildObjectGroup = $this->getChild($idParent);
            foreach ($ChildObjectGroup as $child) {
                $id = $child->id;
                $name = $child->name;
                $code = $child->code;
                $parentName =  $parentName;
                $users_id = $child->users_id;
                $type = 'unit';
                $children = $this->getChildByParent(compact("id", "name"));
                $result[$i] = compact($this->column);
                $i++;
            }
            // Lấy danh sách người dùng
            if ($this->checkStaffByGroup($idParent)) {
                $users = $this->getStaffByGroup($idParent);
                foreach ($users as $user) {
                    $id = $user->id;
                    $name = $user->name;
                    $code = $user->code;
                    $parentName =  $parentName;
                    $users_id = $user->users_id;
                    $type = 'user';
                    $children = Null;
                    $result[$i] = \compact($this->column);
                    $i++;
                }
            }
            return $result;
        } else {
            // Lấy danh sách người dùng
            if ($this->checkStaffByGroup($idParent)) {
                $users = $this->getStaffByGroup($idParent);
                foreach ($users as $user) {
                    $id = $user->id;
                    $name = $user->name;
                    $code = $user->code;
                    $parentName =  $parentName;
                    $users_id = $user->users_id;
                    $type = 'user';
                    $children = Null;
                    $result[$i] = \compact($this->column);
                    $i++;
                }
                return $result;
            }
        }
    }
}
