<?php

namespace Modules\Api\Requests;

use Modules\Core\Efy\Http\Requests\ApiRequest;

class AuthRequest extends ApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required',
            'password' => 'required'
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
            'username.required' => 'Username không được để trống',
            'password.required' => 'Password không được để trống'
        ];
    }
}
