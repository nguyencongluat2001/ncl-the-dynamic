<?php


namespace Modules\Api\Requests\Users;


use Modules\Core\Efy\Http\Requests\ApiRequest;

class UserRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password_old'     => 'required',
            'password'         => 'required|min:8|max:52|regex:/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/',
            'password_comfirm' => 'required|same:password',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'password_old.required'     => 'Mật khẩu cũ không được để trống',
            'password.required'         => 'Mật khẩu mới không được để trống',
            'password_comfirm.required' => 'Mật khẩu xác nhận không được để trống',
            'password_comfirm.same'     => 'Mật khẩu xác nhận không khớp với mật khẩu mới',
            'password.min'              => 'Mật khẩu mới có ít nhất 8 ký tự',
            'password.max'              => 'Mật khẩu mới có nhiều nhất 52 ký tự',
            'password.regex'            => 'Mật khẩu mới không đúng định dạng',
        ];
    }
}
