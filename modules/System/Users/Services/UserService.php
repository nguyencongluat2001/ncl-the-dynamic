<?php

namespace Modules\System\Users\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Core\Ncl\Http\BaseService;
use Modules\Core\Ncl\Library;
use Modules\Core\Ncl\LoggerHelpers;
use Modules\System\Users\Models\PositionModel;
use Modules\System\Users\Models\UnitModel;
use Modules\System\Users\Models\UserModel;
use Modules\System\Users\Repositories\UserRepository;

/**
 * Xử lý logic người dùng
 * 
 * @author luatnc
 */
class UserService extends BaseService
{
    private $userRepository;
    private $logger;

    public function __construct(
        UserRepository $u,
        LoggerHelpers $l
    ) {
        $this->userRepository = $u;
        $this->logger = $l;
        $this->logger->setFileName('System_UserService');
        parent::__construct();
    }
    public function repository()
    {
        return UserRepository::class;
    }

    /**
     * Lấy dữ liệu màn index
     * 
     * @return array
     */
    public function index(): array
    {
        $objLibrary = new Library;
        $arrResult = array();
        $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('js', 'assets/chosen/chosen.jquery.min.js,assets/jquery-ui-1.10.4.custom.min.js,assets/jstree/jstree.min.js,assets/jstree/jstreetable.js,System/Users/Js_User.js,System/Users/JS_Tree.js,System/Users/JS_TempHtml.js,assets/jquery.validate.js', ',', $arrResult);
        $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('css', 'assets/chosen/bootstrap-chosen.css,assets/jquery-ui/jquery-ui.min.css,assets/tree/style.min.css', ',', $arrResult);
        $data['strJsCss'] = json_encode($arrResult);
        return $data;
    }

    /**
     * Lấy các đơn vị khi load lần đầu
     * 
     * @param array $input
     * @return array
     */
    public function loadList(array $input): array
    {
        $data['datas'] = $this->userRepository->_getAll($input);
        return array(
            'data' => view("Users::User.loadList", $data)->render(),
            'perPage' => $input['offset'],
        );
    }

    /**
     * Dữ liệu màn hình tạo mới người dùng
     * 
     * @param array $input
     * @return array
     */
    public function _create(array $input): array
    {
        $data['data'] = new UserModel();
        $data['data']->role = 'ADMIN_OWNER';
        $data['id'] = '';
        $data['check'] = 'checked';
        $data['order'] = UserModel::select('id')->count() + 1;
        return $data;
    }

    /**
     * Dữ liệu màn hình sửa người dùng
     * 
     * @param array $input
     * @return array
     */
    public function edit(array $input): array
    {
        $data['id'] = $input['itemId'];
        $user = UserModel::find($input['itemId']);
        $data['check'] = $user->status == 1 ? 'checked' : '';
        if ($user->role !== 'ADMIN_SYSTEM' && $user->role !== 'ADMIN_OWNER' && $user->role !== 'ADMIN_REPORT') {
            $user->role = 'USER';
        }
        $data['data'] = $user;
        // $data['parent_department'] = $user->units_id;
        // $department = UnitModel::find($data['parent_department']);
        // $data['departments'] = $this->getDepartment($department->units_id);
        // $unit = UnitModel::find($department->units_id);
        // $data['departmentname'] = $department->name;
        // $data['departmentcode'] = $department->code;
        // $data['unitname'] = $unit->name;

        return $data;
    }

    /**
     * Cập nhật người dùng
     * 
     * @param array $input
     * @return array
     */
    public function _update(array $input): array
    {
        if(!empty($input['id'])){
            $sql = UserModel::find($input['id']);
            $sql->updated_at = date('Y-m-d H:i:s');
        }else{
            $sql = new UserModel;
            $sql->id = (string)\Str::uuid();
            $sql->created_at = date('Y-m-d H:i:s');
            if(UserModel::where('username', $input['username'])->exists()){
                return array('success' => false, 'message' => 'Tên người dùng đã tồn tại, vui lòng nhập tên khác!');
            }
        }
        $birthday = Carbon::createFromFormat('d/m/Y', $input['birthday'])->format('Y-m-d');
        $sql->name = $input['name'];
        // $sql->address = $input['address'] ?? '';
        $sql->email = $input['email'] ?? null;
        $sql->mobile = $input['mobile'] ?? null;
        $sql->fax = $input['fax'] ?? null;
        $sql->username = $input['username'];
        !empty($input['password']) ? $sql->password = Hash::make($input['password']) : null;
        $sql->order = $input['order'] ?? null;
        $sql->status = isset($input['status']) ? 1 : 0;
        $sql->role = $input['role'] ?? null;
        $sql->sex = $input['sex'] ?? null;
        $sql->birthday = $birthday;
        
        // DB::beginTransaction();
        $sql->save();
        return array('success' => true, 'message' => 'Cập nhật thành công');



        // if (empty($input['status'])) $input['vaitro'] = '';
        // $id = $input['id'];
        // if ($this->userRepository->existsUser($id, $input['username']))
        //     return array('success' => false, 'message' => 'Tên người dùng đã tồn tại, vui lòng nhập tên khác!');
        // $this->logger->setChannel('UPDATE_USER');
        // try {
        //     if ($id !== '') $this->logger->log('Before update', UserModel::find($id)->toArray());
        //     $userId = $this->userRepository->update($id, $input);
        //     $this->userRepository->updateOrder($input['department'], $userId, $input['oder']);
        //     DB::commit();
        //     if ($id !== '') $this->logger->log('After update', UserModel::find($id)->toArray());
        //     return array('success' => true, 'message' => 'Cập nhật thành công', 'parent_id' => $input['department']);
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     $this->logger->log('Exception: ' . $e->getMessage() . ' on file ' . $e->getFile() . ' in line ' . $e->getLine());
        //     return array('success' => false, 'message' => 'Cập nhật thất bại');
        // }
    }

    /**
     * Xóa người dùng
     * 
     * @param array $input
     * @return array
     */
    public function _delete(array $input): array
    {
        $arrIds = explode(',', $input['itemId']);
        $this->logger->setChannel('DELETE_USER');
        DB::beginTransaction();
        try {
            foreach($arrIds as $key => $id){
                $user = UserModel::find($id)->delete();
                DB::commit();
                $this->logger->log('Deleted', $user);
            }
            return array('success' => true, 'message' => 'Xóa thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logger->log('Exception: ' . $e->getMessage() . ' on file ' . $e->getFile() . ' in line ' . $e->getLine());
            return array('success' => false, 'message' => 'Xóa thất bại');
        }
    }

    /**
     * Tìm kiếm
     * 
     * @param array $input
     * @return array
     */
    public function search(array $input): array
    {
        // $idunit = $input['idunit'];
        $type = $input['param'];
        $search = $input['search'];
        $data = UserModel::join('position', 'users.position_id', '=', 'position.id')
            ->select(['users.id', 'users.name', 'role', 'username', 'users.order', 'address', 'users.status', 'position.code as position'])
            ->where("users.name", "like", "%$search%")->orWhere('users.username', "like", "%$search%")
            ->orderBy('users.order', 'asc')
            ->get()->toArray();
        $return['type'] = $type;
        $return['data'] = $data;

        return $return;
    }
}
