<?php

namespace Modules\System\Users\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Core\Ncl\Http\BaseRepository;
use Modules\System\Users\Models\UserModel;

class UserRepository extends BaseRepository
{

    private $userModel;

    public function __construct(UserModel $u)
    {
        $this->userModel = $u;
        parent::__construct();
    }

    public function model()
    {
        return UserModel::class;
    }

    /**
     * @param array $input
     * 
     * @return array
     */
    public function _getAll($input)
    {
        return $this->userModel->_getAll($input);
    }
    /**
     * Cập nhật người dùng
     * 
     * @param mixed $id
     * @param array $data
     * @return bool
     */
    public function _update($id, array $data): string
    {
        $checkpass = true;
        // update user
        if ($id !== '') {
            $checkpass = false;
            $userModel = $this->userModel->find($id);
            $userModel->updated_at = date('Y-m-d H:i:s');
            if ($data['password'] !== '' && $data['password'] === $data['repassword']) {
                $checkpass = true;
            }
        } else {
            $userModel = new $this->userModel;
            $id        = Str::uuid()->toString();
            $userModel->id         = $id;
            $userModel->created_at = date('Y-m-d H:i:s');
        }

        if ($checkpass) $userModel->password = Hash::make($data['password']);
        $userModel->units_id    = $data['department'];
        $userModel->position_id = $data['position'];
        $userModel->name        = $data['name'];
        $userModel->email       = $data['email'];
        $userModel->mobile      = $data['phone_number'];
        $userModel->username    = $data['username'];
        $userModel->order       = $data['oder'];
        $userModel->status      = $data['status'] === 'on' ? 1 : 0;
        $userModel->role        = $data['vaitro'];
        $userModel->save();

        return $userModel->id;
    }

    /**
     * Cập nhật lại thứ tự
     * 
     * @param string $unitId
     * @param string $userId
     * @param int|string $order
     * @return void
     */
    public function updateOrder(string $unitId, string $userId, int|string $order): void
    {
        $users = $this->userModel->where("order", ">=", $order)
            ->where("units_id", $unitId)
            ->where("id", '<>', $userId)
            ->orderBy('order', 'asc')
            ->get(['id', 'order'])->toArray();
        $i = $order;
        foreach ($users as $user) {
            $i++;
            $this->userModel->find($user['id'])->update(['order' => $i]);
        }
    }

    /**
     * Kiểm tra đã tồn tại người dùng chưa
     * 
     * @param string $id
     * @param string $username
     * @return bool
     */
    public function existsUser($id, $username): bool
    {
        $q = UserModel::where("username", $username);
        if ($id !== '') $q->where("id", '<>', $id);
        return $q->exists();
    }

    /**
     * Xóa người dùng
     * 
     * @param string $id
     * @return string $parentId
     */
    public function _delete(string $id): string
    {
        $user = $this->userModel->find($id);
        $parentId = $user->units_id;
        $user->delete();

        return $parentId;
    }


    // Bảng UserModel

    public function getByUserName($username)
    {
        return $this->userModel::select('*')->where('username', $username)->first();
    }

    public function getUserByDepartmnet($unitId, $arrcolumn = array('*'))
    {
        $arrusers = DB::table('users')
            ->join('position', 'users.position_id', '=', 'position.id')
            ->select('users.*', 'position.code')
            ->where('units_id', $unitId)
            ->get();

        return $arrusers;
        //return UserModel::select($arrcolumn)->where('units_id', $unitId)->get();
    }

    public function checkUserByDepartment($unitId)
    {

        return UserModel::where("units_id", $unitId)->count();
    }
}
