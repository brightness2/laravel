<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:6',
            // 'name' => 'bail|required|max:6',/bail规则时当有一个错误时不会校验其它
            // 'user.age' => 'required',//可以是嵌套参数
            // 'size' => 'nullable|max:20',//nullable规则表示字段可选
        ];
    }
}
