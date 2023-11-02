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
            if (isset($input['next_url']) && $input['next_url'] != '') {
                if (str_contains($input['next_url'], '/bai-thi/')) {
                    $isTakenTest = $this->examService->checkTakenTest(['contest_id' => preg_replace('/^\/bai-thi\//', '', $input['next_url'])]);
                    if ($isTakenTest['is_taken_test'] === false) {
                        $url .= $input['next_url'];
                    }
                } else $url .= $input['next_url'];
            }
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
        $email = $input['email'];
        $password = $input['password'];
        if (Auth::guard('frontend')->attempt(['email' => $email, 'password' => $password])) {
            $user = Auth::guard('frontend')->user();
            $_SESSION["hoithicchc"]["id"]         = $user->id;
            $_SESSION["hoithicchc"]["email"]      = $email;
            $_SESSION["hoithicchc"]["name"]       = $user->ho_ten;
            $_SESSION["hoithicchc"]["cmnd"]       = $user->cmnd;
            $_SESSION["hoithicchc"]["unit"]       = $user->don_vi;
            $_SESSION["hoithicchc"]["cap_don_vi"] = $user->cap_don_vi;
            return true;
        } else {
            $data['message'] = "Sai tên đăng nhập hoặc mật khẩu!";
            return $data;
        }
    }

    public function signUp(array $input): array
    {
        if ($input['ho_ten'] == '') return ['success' => false, 'message' => 'Họ tên không được để trống!'];
        if ($input['cmnd'] == '') return ['success' => false, 'message' => 'Chứng minh nhân dân không được để trống!'];
        if ($input['email'] == '') return ['success' => false, 'message' => 'Email không được để trống!'];
        if ($input['cap_don_vi'] == '' || $input['don_vi'] == '') return ['success' => false, 'message' => 'Đơn vị không được để trống!'];
        if ($input['email'] == '@haiduong.gov.vn' || $input['email'] == '') {
            return ['success' => false, 'message' => 'Email không được để trống!'];
        }
        $getUser = $this->subjectService->where('trang_thai', 1)->where('email', $input['email'])->first();
        if ($getUser != null) return ['success' => false, 'message' => 'Email đã được đăng ký tài khoản!'];
        $password = randomString(6);
        $input['password'] = Hash::make($password);
        $created = $this->subjectService->create($input);
        if ($created) {
            $data['mailto'] = $input['email'];
            $data['ho_ten'] = $input['ho_ten'];
            $data['subject'] = 'Hội thi trực tuyến tìm hiểu về công tác CCHC tỉnh Hải Dương thông báo cung cấp tài khoản!';
            $data['password'] = $password;
            (new ForgetPassWordMailHelper())->send_otp($data);
        }

        return ['success' => true, 'message' => 'Đăng ký thành công! Vui lòng truy cập email để nhận mật khẩu!'];
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
