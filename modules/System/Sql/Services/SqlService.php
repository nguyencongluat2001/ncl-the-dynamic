<?php

namespace Modules\System\Sql\Services;

use Modules\Base\Service;
use Modules\System\Sql\Repositories\SqlRepository;

class SqlService extends Service
{
    public function __construct()
    {
        parent::__construct();
    }

    public function repository()
    {
        return SqlRepository::class;
    }
    public function _update($input)
    {
        if(isset($input['id']) && !empty($input['id'])){
            $id = $input['id'];
            $param['updated_at'] = date('Y-m-d H:i:s');
        }else{
            $id = strtoupper((string)\Str::uuid());
            $param['created_at'] = date('Y-m-d H:i:s');
        }
        $param = [
            'id' => $id,
            'parent_id' => $input['parent_id'] ?? null,
            'question' => $input['question'] ?? null,
            'answer' => $input['answer'] ?? null,
            'order' => $input['order'] ?? null,
            'status' => isset($input['status']) ? 1 : 0,
        ];
        if(isset($input['id']) && !empty($input['id'])){
            $param['updated_at'] = date('Y-m-d H:i:s');
            $this->repository->where('id', $input['id'])->update($param);
            return array('success' => true, 'message' => 'Cập nhật thành công!');
        }else{
            $param['created_at'] = date('Y-m-d H:i:s');
            $this->repository->insert($param);
            return array('success' => true, 'message' => 'Thêm mới thành công!');
        }

    }

}
