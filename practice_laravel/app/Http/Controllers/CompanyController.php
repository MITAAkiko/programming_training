<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;//getやpostを受け取れる？
use App\Models\Company;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    private $cmpMdl;
    public function __construct()
    {
        $this->cmpMdl = new Company;//モデル
    }
    public function index(Request $get)
    {
        $datas = $this->cmpMdl->fetchData();
        $prefecture = config('config.PREFECTURES');
        
        $search = $get->input('search');
        if ($get->has('search')) {
            $datas = $this->cmpMdl->fetchDataSearched($search);
        }

        return view('index', compact('datas', 'prefecture', 'search'));
    }

    public function add(Request $post)
    {
        $posts = [
            'name' => $post->input('name'),
            'manager' => $post->input('manager'),
            'phone' => $post->input('phone'),
            'postal' => $post->input('postal_code'),
            'prefecture_code' => $post->input('prefecture_code'),
            'address' => $post->input('address'),
            'email' => $post->input('email'),
            'prefix' => $post->input('prefix'),
        ];
        if (!empty($post)) {
           // $error = $this->cmpMdl->
        }

        $prefecture = config('config.PREFECTURES');
        return view('add', compact('prefecture', $posts));
    }

    public function validation(Request $post)
    {
        $validator = Validator::make($post->all(), [//第1引数input　第２引数ルール　第３引数メッセージ　第４引数:attributeの中に入れる文字の設定
            'name' => 'required|max:64', //空文字、64文字
            'manager' => 'required|max:32',
            'phone' => 'required|digits_between:1,11',
            'postal' => 'required|digits:7',
            'prefecture_code' => 'required|max:47|min:1',//エラー文字登録していない
            'address' => 'required|max:100',
            'email' => 'required|email:rfc,dns',
            'prefix' => 'required|alpha_num|max:16'//一旦全角OK
        ], [
            'required' => '入力してください',
            'max' => ':max 文字で入力してください',
            'integer' => '数字で入力してください',
            'digits' => ':digits 桁の数字で入力してください',
            'size' => ':size 文字で入力してください',
            'email' => '正しく入力してください',
            'alpha_num' => '英数字で入寮してください',
            'digits_between' => '11桁以内で入力してください',
        ]);
        if ($validator->fails()) {
            return redirect('/add')
            ->withErrors($validator, 'add')//addという名前を付けると$errors->add->で使える
            ->withInput();//oldにいれる
        } else {
            $validated = $post->validated();
            $validated = $post->safe()->all();//これをSQLに登録
            $this->cmpMdl->create($validated);
            dd($validated);
            return view('index');
        }
    }
}
