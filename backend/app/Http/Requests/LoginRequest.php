<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => 'required|string|email',
            'password' => 'required|string|min:8|max:32',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Eメールは必須です',
            'password.required' => 'パスワードは必須です',
            'password.min' => 'パスワードは8文字以上で入力してください',
            'password.max' => 'パスワードは32文字以下で入力してください',
        ];
    }
}
