<?php
namespace Modules\Frontend\Services\Dashboard;

use Illuminate\Support\Facades\Hash;
use Modules\Base\Service;
use Modules\Frontend\Repositories\Dashboard\CateRepository;
use Str;

class CateService extends Service
{

    public function __construct(
        CateRepository $CateRepository
        )
    {
        parent::__construct();
        $this->CateRepository = $CateRepository;
        parent::__construct();
    }

    public function repository()
    {
        return CateRepository::class;
    }

    public function store($input){
        $cates = $this->repository->select('*')->get();
        if($input['id'] != ''){
            $arrData = [
                'name'=>$input['name'],
                'code_cate'=>$input['code_cate'],
                'decision'=>$input['decision'],
                'order'=> isset($input['order']) && !empty($input['order']) ? $input['order'] :count($cates) + 1,
                'status'=> isset($input['status']) && !empty($input['status']) ? 1 : 0,
            ];
            $create = $this->CateRepository->where('id',$input['id'])->update($arrData);
        }else{
            $countCate = $this->repository->select('*')->where('code_cate', $input['code_cate'])->count();
            if($countCate > 0){
                return array('success' => false, 'message' => 'Mã đối tượng đã tồn tại!');
            }
            $arrData = [
                'id'=>(string)Str::uuid(),
                'name'=>$input['name'],
                'code_cate'=>$input['code_cate'],
                'decision'=>$input['decision'],
                'order'=> count($cates) + 1,
                'status'=> isset($input['status']) && !empty($input['status']) ? 1 : 0,
            ];
            $create = $this->CateRepository->create($arrData);
        }
        
        return array('success' => true, 'message' => 'Cập nhật thành công!');
    }
    public function loadList($arrInput){
        $data = array();
        $param = $arrInput;
        $objResult = $this->repository->filter($param);
        $data['datas'] = $objResult;
        // dd($data);
        $data['param'] = $param;
        $data['pagination'] = $data['datas']->links('pagination.default');
        // return $create;
    }
    public function editCategory($arrInput){
        $getCategoryInfor = $this->repository->where('id',$arrInput['chk_item_id'])->first()->toArray();
        return $getCategoryInfor;
    }
    /**
     * Cập nhật và thêm mới màn hình danh sách
     */
    public function _updateCategory($input, $id)
    {
        if(isset($input['order']) && !empty($input['order'])){
            $this->updateOrder($input);
        }
        $CateSingle = $this->repository->where('id', $id)->first();
        $cates = $this->repository->select('*')->get();
        $code_cate = isset($CateSingle) && !empty($CateSingle->code_cate) ? $CateSingle->code_cate : "";
        $name = isset($CateSingle) && !empty($CateSingle->name) ? $CateSingle->name : "";
        $decision = isset($CateSingle) && !empty($CateSingle->decision) ? $CateSingle->decision : "";
        $order = isset($CateSingle) && !empty($CateSingle->order) ? $CateSingle->order : count($cates) + 1;
        $param = [
            'code_cate' => isset($input['code_cate']) ? $input['code_cate'] : $code_cate,
            'name' => isset($input['name']) ? $input['name'] : $name,
            'decision' => isset($input['decision']) ? $input['decision'] : $decision,
            'order' => isset($input['order']) ? $input['order'] : $order,
        ];
        foreach($cates as $value){
            if(isset($input['code_cate']) && $input['code_cate'] === $value->code_cate){
                return array('success' => false, 'message' => 'Mã đối tượng đã tồn tại!');
            }
        }
        if(isset($CateSingle) && !empty($CateSingle)){
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
        $query = $this->repository->select('*')->where('order', '>=', $input['order'])->orderBy('order');
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
