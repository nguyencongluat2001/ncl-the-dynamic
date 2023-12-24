<?php

namespace Modules\Frontend\Services\Dashboard;

use Illuminate\Support\Facades\Hash;
use Modules\Base\Service;
use Modules\Frontend\Repositories\Dashboard\CategoryRepository;
use Str;

class CategoryService extends Service
{

    public function __construct(
        CategoryRepository $CategoryRepository
        )
    {
        parent::__construct();
        $this->CategoryRepository = $CategoryRepository;
    }

    public function repository()
    {
        return CategoryRepository::class;
    }

    public function store($input){
        $categories = $this->repository->select('*')->get();
        if(isset($input['order']) && !empty($input['order'])){
            $this->updateOrder($input);
        }
        if($input['id'] != ''){
            $arrData = [
                'cate'=> $input['cate'] ,
                'name_category'=> $input['name_category'] ,
                'code_category'=> $input['code_category'] ,
                'decision'=> $input['decision'] ,
                'order' => isset($input['order']) && !empty($input['order']) ? trim($input['order']) : count($categories) + 1,
                'status' => isset($input['status']) && !empty($input['status']) ? 1 : 0,
            ];
            $create = $this->CategoryRepository->where('id',$input['id'])->update($arrData);
        }else{
            $countCategory = $this->repository->where('code_category', $input['code_category'])->where('cate', $input['cate'])->count();
            if($countCategory > 0){
                return array('success' => false, 'message' => 'Mã đối tượng đã tồn tại!');
            }
            $arrData = [
                'id'=>(string)Str::uuid(),
                'cate'=> $input['cate'] ,
                'name_category'=> $input['name_category'] ,
                'code_category'=> $input['code_category'] ,
                'decision'=> $input['decision'] ,
                'order' => isset($input['order']) && !empty($input['order']) ? trim($input['order']) : count($categories) + 1,
                'status' => isset($input['status']) && !empty($input['status']) ? 1 : 0,
            ];
            $create = $this->CategoryRepository->create($arrData);
        }
        
        return array('success' => true, 'message' => 'Cập nhật thành công');
    }
    /**
     * Cập nhật và thêm mới màn hình danh sách
     */
    public function _updateCategoryCate($input, $id)
    {
        if(isset($input['order']) && !empty($input['order'])){
            $this->updateOrder($input);
        }
        $CategorySingle = $this->repository->where('id', $id)->first();
        $categories = $this->repository->where('cate', $input['cate'])->get();
        $cate = isset($CategorySingle) && !empty($CategorySingle->cate) ? $CategorySingle->cate : "";
        $code_category = isset($CategorySingle) && !empty($CategorySingle->code_category) ? $CategorySingle->code_category : "";
        $name_category = isset($CategorySingle) && !empty($CategorySingle->name_category) ? $CategorySingle->name_category : "";
        $decision = isset($CategorySingle) && !empty($CategorySingle->decision) ? $CategorySingle->decision : "";
        $order = isset($CategorySingle) && !empty($CategorySingle->order) ? $CategorySingle->order : count($categories) + 1;
        $param = [
            'cate' => isset($input['cate']) ? $input['cate'] : $cate,
            'code_category' => isset($input['code_category']) ? $input['code_category'] : $code_category,
            'name_category' => isset($input['name_category']) ? $input['name_category'] : $name_category,
            'decision' => isset($input['decision']) ? $input['decision'] : $decision,
            'order' => isset($input['order']) ? $input['order'] : $order,
        ];
        foreach($categories as $value){
            if(isset($input['code_category']) && $input['code_category'] === $value->code_category){
                return array('success' => false, 'message' => 'Mã đối tượng đã tồn tại!');
            }
        }
        if(isset($CategorySingle) && !empty($CategorySingle)){
            $this->repository->where('id',$id)->update($param);
            return array('success' => true, 'message' => 'Cập nhật thành công!');
        }else{
            $param['status'] = 1;
            $param['id'] = $id;
            $this->repository->insert($param);
            return array('success' => true, 'message' => 'Thêm mới thành công!');
        }
    }
    /**
     * Cập nhật thứ tự
     */
    public function updateOrder($input)
    {
        $query = $this->repository->select('*')->where('order', '>=', $input['order'])->where('cate', $input['cate'])->orderBy('order');
        if(isset($input['id'])){
            $query = $query->where('id', '<>', $input['id']);
        }
        $order = $query->get();
        if(!empty($order)){
            $i = $input['order'];
            foreach($order as $value){
                $i++;
                $this->repository->where('id', $value->id)->update(['order' => $i]);
            }

        }
    }

}
