<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'username' => 'required|string|max:16',
            'email' => 'required|string|email|max:255|regex:/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/',
            'password' => 'required|string|min:8|max:32|regex:/^[A-Za-z0-9]+$/',
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'ユーザー名は必須です',
            'username.max' => 'ユーザー名は16文字以内で入力してください',
            'email.required' => 'Eメールは必須です',
            'email.email' => '有効なEメールを入力してください',
            'email.regex' => '有効なEメールを入力してください',
            'password.required' => 'パスワードは8文字以上で入力してください',
            'password.min' => 'パスワードは8文字以上で入力してください',
            'password.max' => 'パスワードは32文字以下で入力してください',
            'password.regex' => 'パスワードは半角英数字のみで入力してください',
        ];
    }
}
