<?php

namespace Modules\System\Sql\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\System\Category\Services\CategoryService;
use Modules\System\Sql\Services\SqlService;
use Illuminate\Support\Facades\DB;
class SqlController extends Controller
{
    public $SqlService;
    public $categoryService;
    public function __construct(SqlService $SqlService, CategoryService $categoryService)
    {
        $this->SqlService = $SqlService;
        $this->categoryService = $categoryService;
    }
    public function index()
    {
        $data['categories'] = $this->categoryService->where('cate', 'DM_Sql')->orderBy('order', 'desc')->get();
        return view('Sql::index', $data);
    }
    public function loadList(Request $request)
    {
        $arrInput = $request->all();
        $statement = $arrInput['search'];
        $data['datas'] = DB::select($statement);
        return view('Sql::loadList', $data);
    }
    /**
     * Thêm/Sửa
     */
    public function add(Request $request)
    {
        $arrInput = $request->all();
        if(isset($arrInput['id']) && !empty($arrInput['id'])){
            $data['datas'] = $this->SqlService->where('id', $arrInput['id'])->first();
            $data['_id'] = $arrInput['id'];
        }else{
            $data['parent_id'] = $arrInput['parent_id'];
        }
        $data['order'] = $this->SqlService->select('id')->count() + 1;
        $data['categories'] = $this->categoryService->where('cate', 'DM_Sql')->orderBy('order', 'desc')->get();
        return view('Sql::add', $data);
    }
    /**
     * Cập nhật
     */
    public function update(Request $request)
    {
        $arrInput = $request->all();
        $data = $this->SqlService->_update($arrInput);
        return $data;
    }
    /**
     * Cập nhật
     */
    public function delete(Request $request)
    {
        $input = $request->all();
        $listids = trim($input['listitem'], ",");
        $ids = explode(",", $listids);
        foreach ($ids as $id) {
            if ($id) {
                $this->SqlService->where('id',$id)->delete();
            }
        }
        return array('success' => true, 'message' => 'Xóa thành công');
    }
}