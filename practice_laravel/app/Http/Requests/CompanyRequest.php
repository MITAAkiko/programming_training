<?php

namespace App\Http\Requests;

use App\Rules\AlphaNumCheck;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class CompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        'name' => 'required|max:64', //空文字、64文字
        'manager' => 'required|max:32',
        'phone' => 'required|digits_between:1,11',
        'postal' => 'required|digits:7',
        'prefecture_code' => 'required|max:47|min:1',//エラー文字登録していない
        'address' => 'required|max:100',
        'email' => 'required|email:rfc,dns',
        'prefix' => 'required|max:16',
        'prefix' => [new AlphaNumCheck()]//英数字チェック
        ];
    }
    
    public function messages()
    {
        return[
            'required' => '入力してください',
            'max' => ':max 文字で入力してください',
            'integer' => '数字で入力してください',
            'digits' => ':digits 桁の半角数字で入力してください',
            'size' => ':size 文字で入力してください',
            'email' => '正しく入力してください',
            'digits_between' => '11桁以内で入力してください',
        ];
    }
}
