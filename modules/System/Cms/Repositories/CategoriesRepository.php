<?php

namespace Modules\System\Cms\Repositories;

use Modules\Core\Efy\Http\BaseRepository;
use Modules\System\Cms\Models\CategoriesModel;

class CategoriesRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
    }
    public function model()
    {
        return CategoriesModel::class;
    }
    /**
     * Cập nhật người dùng
     * 
     * @param mixed $id
     * @param array $data
     * @return bool
     */
    public function _update(array $data): string
    {
        if($data['id'] != ''){
            $sql = $this->model->find($data['id']);
            $sql->updated_at = date('Y-m-d H:i:s');
        }else{
            $sql = new $this->model;
            $sql->id = (string)\Str::uuid();
            $sql->created_at = date('Y-m-d H:i:s');
        }
        if($data['order'] != ''){
            $this->updateOrder($data);
        }
        $sql->parent_id = $data['parent_id'] ?? null;
        $sql->name = $data['name'] ?? null;
        $sql->id_menu = $data['id_menu'] ?? null;
        $sql->slug = $data['slug'] ?? null;
        $sql->layout = $data['layout'] ?? null;
        $sql->icon = $data['icon'] ?? null;
        $sql->category_type = $data['category_type'] ?? null;
        $sql->owner_code = $data['owner_code'] ?? null;
        $sql->is_display_at_home = isset($data['is_display_at_home']) && $data['is_display_at_home'] === 'on' ? 1 : 0;
        $sql->order = $data['order'] ?? null;
        $sql->status = isset($data['status']) && $data['status'] === 'on' ? 1 : 0;
        // dd($sql);
        $sql->save();
        return $sql->id;
    }
    /**
     * Cập nhật stt
     */
    public function updateOrder($input)
    {
        $query = $this->model->select('*')->where('order', '>=', $input['order'])->where('parent_id', $input['parent_id'])->orderBy('order');
        if(isset($input['id']) && !empty($input['id'])){
            $query = $query->where('id', '<>', $input['id']);
        }
        $order = $query->get();
        // dd($order);
        if(!empty($order)){
            $i = $input['order'];
            foreach($order as $value){
                $i++;
                $this->model->where('id', $value->id)->update(['order' => $i]);
            }

        }
    }
    /**
     * Xóa
     */
    public function _delete($data)
    {
        $categories = $this->model->where('parent_id', $data->id)->get();
        $data->delete();
        foreach($categories as $key => $value){
            $this->_delete($value);
        }
    }
}