<?php

namespace Modules\Frontend\Services;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Core\Helpers\ForgetPassWordMailHelper;

class AuthService
{
    private $subjectService;
    private $examService;

    public function __construct(
        SubjectService $s,
        ExamService $e
    ) {
        $this->subjectService = $s;
        $this->examService = $e;
    }

    /**
     * Đăng nhập
     * 
     * @param array $input
     * @return array
     */
    public function signIn(array $input): array
    {
        // if($email == '' || $email == null){
        //     $data['email'] = "Email không được để trống";
        //     return view('auth.signin',compact('data'));
        // }
        // if($password == '' || $password == null){
        //     $data['password'] = "Mật khẩu không được để trống";
        //     return view('auth.signin',compact('data'));
        // }
        $data = $this->handleSignIn($input);
        if ($data === true) {
            $url = url('');
            return array('success' => true, 'url' => $url);
        } else {
            return array('success' => false, 'url' => url('dang-nhap'), 'message' => $data['message']);
        }
    }

    /**
     * Xử lý đăng nhập và tạo session
     * 
     * @param array $input
     * @return bool|array
     */
    public function handleSignIn(array $input): bool|array
    {
        $phone = $input['phone'];
        $password = $input['password'];
        if (Auth::guard('frontend')->attempt(['phone' => $phone, 'password' => $password])) {
            $user = Auth::guard('frontend')->user();
            $_SESSION["id"]         = $user->id;
            $_SESSION["email"]      = $user->email;
            $_SESSION["name"]       = $user->name;
            $_SESSION["phone"]      = $user->phone;
            $_SESSION["address"]    = $user->address;
            return true;
        } else {
            $data['message'] = "Sai tên đăng nhập hoặc mật khẩu!";
            return $data;
        }
    }

    public function signUp(array $input): array
    {
        if ($input['name'] == '' || $input['name'] == null) return ['success' => false, 'message' => 'Họ tên không được để trống!'];
        if ($input['phone'] == '' || $input['phone'] == null) return ['success' => false, 'message' => 'Chứng minh nhân dân không được để trống!'];
        // if ($input['email'] == '' || $input['email'] == null) return ['success' => false, 'message' => 'Email không được để trống!'];
        if ($input['password'] == '' || $input['password'] == null) return ['success' => false, 'message' => 'password không được để trống!'];
        if ($input['confirmPassword'] == '' || $input['confirmPassword'] == null) return ['success' => false, 'message' => 'Nhập lại mật khẩu không được để trống!'];
        if ($input['password'] != $input['confirmPassword'] ) return ['success' => false, 'message' => 'Nhập lại mật khẩu không khớp!'];

        $getUser = $this->subjectService->where('trang_thai', 1)->where('phone', $input['phone'])->first();
        if ($getUser != null) return ['success' => false, 'message' => 'Số điện thoại đã được đăng ký tài khoản!'];
        if(!empty($input['email'])){
            $getUser = $this->subjectService->where('trang_thai', 1)->where('email', $input['email'])->first();
            if ($getUser != null) return ['success' => false, 'message' => 'Email đã được đăng ký tài khoản!'];
        }
        $input['password'] = Hash::make($input['password']);
        $created = $this->subjectService->create($input);
        $url = url('login');
        return ['success' => true, 'message' => 'Đăng ký thành công!','url' => $url];
    }

    /**
     * Cấp lại mật khẩu
     * 
     * @param string $email
     * @return array
     */
    public function forgotPassword(string $email): array
    {
        // check xem có tồn tại email không
        $subject = $this->subjectService->where('email', $email)->first();
        if (!$subject) {
            return array('success' => false, 'title' => 'Email chưa được đăng ký!', 'redirect_to' => '/dang-ky');
        }
        try {
            $password = randomString(6);
            $hashPassword = Hash::make($password);
            $updated = $this->subjectService->where('email', $email)->update(['password' => $hashPassword]);
            if ($updated) {
                $data['mailto'] = $email;
                $data['subject'] = 'Hội thi trực tuyến tìm hiểu về công tác CCHC tỉnh Hải Dương thông báo cấp lại mật khẩu!';
                $data['password'] = $password;
                $data['view'] = 'emails.forgot-password';
                (new ForgetPassWordMailHelper())->send_otp($data);
            }

            return array('success' => true, 'title' => 'Lấy mật khẩu thành công!', 'text' => 'Vui lòng truy cập vào email để nhận mật khẩu', 'redirect_to' => '/dang-nhap');
        } catch (Exception $e) {
            return array('success' => false, 'title' => 'Đã xảy ra lỗi!');
        }
    }
}
