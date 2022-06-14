<?php

namespace App\Http\Requests;

class Messages extends CompanyRequest
{
    public function companyMessages()
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
