<?php

namespace App\Http\Requests;

use App\Rules\AlphaNumCheck;
use App\Rules\DateCheck;

class InvoiceRequest extends CommonRequest
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
        'title' => 'required|max:64',
        'total' => 'required|digits_between:1,10',
        'pay' => ['required', 'digits:8', new DateCheck, 'after:date'],
        'date' => ['required', 'digits:8', new DateCheck],
        'quo' => ['required', 'max:100', new AlphaNumCheck()],
        'status' => 'required|digits:1',
        ];
    }
}
