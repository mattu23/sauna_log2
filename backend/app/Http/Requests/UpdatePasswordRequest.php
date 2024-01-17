<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    public function rules()
    {
        return [
            'password' => 'required|string|min:8|max:32|regex:/^[A-Za-z0-9]+$/',
            'newPassword' => 'required|string|min:8|max:32|regex:/^[A-Za-z0-9]+$/',
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'パスワードは必須です',
            'password.min' => 'パスワードは8文字以上で入力してください',
            'password.max' => 'パスワードは32文字以内で入力してください',
            'password.regex' => 'パスワードは半角英数字のみで入力してください',
            'newPassword.required' => '新しいパスワードは必須です',
            'newPassword.min' => '新しいパスワードは8文字以上で入力してください',
            'newPassword.max' => '新しいパスワードは32文字以内で入力してください',
            'newPassword.regex' => '新しいパスワードは半角英数字のみで入力してください',
        ];
    }
}
