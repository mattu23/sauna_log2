<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSaunalogRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

 
    public function rules()
    {
        return [
            'name' => 'required|string|max:20',
            'area' => 'required|string|max:20',
            'rank' => 'required|numeric|min:1|max:5',
            'comment' => 'required|string|max:100',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '名前は入力必須です',
            'name.max' => '名前は20文字以内で入力してください。',
            'area.required' => 'エリアは入力必須です',
            'area.max' => 'エリアは20文字以内で入力してください。',
            'rank.required' => 'ランクは入力必須です',
            'rank.numeric' => 'ランクは数値で入力してください',
            'rank.min' => 'ランクは1以上で入力してください',
            'rank.max' => 'ランクは5以下で入力してください',
            'comment.required' => 'コメントは入力必須です',
            'comment.max' => 'コメントは100文字以内で入力してください。',
        ];
    }
}
