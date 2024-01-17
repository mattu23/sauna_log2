<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'username' => 'required|string|max:16',
            'email' => 'required|string|email|max:255',
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'ユーザー名は必須です',
            'username.max' => 'ユーザー名は16文字以内で入力してください',
            'email.required' => 'Eメールは必須です',
            'email.email' => '有効なEメールを入力してください',
            'email.max' => 'Eメールは255文字以内で入力してください',
        ];
    }
}
