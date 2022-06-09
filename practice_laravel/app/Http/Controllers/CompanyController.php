<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
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
        return view('index', compact('datas', 'prefecture', 'search'));//.blade.phpは省略
    }
    public function add(Request $post)
    {
        $prefecture = config('config.PREFECTURES');
        return view('add', compact('prefecture'));
    }
    public function addValidation(CompanyRequest $post)
    {
        $this->cmpMdl->create($post->safe()->all());
        return redirect('/index');
    }
    public function edit($id, Request $post)
    {
        $data = $this->cmpMdl->fetchDataById($id);
        $prefecture = config('config.PREFECTURES');
    // dd($data);
        return view('edit', compact('prefecture', 'data'));
    }
    public function editValidation($id, CompanyRequest $post)
    {
        $this->cmpMdl->updateData($id, $post->safe()->all());
        return redirect('/index');
    }
    public function delete($id)
    {
        $this->cmpMdl->deleteData($id);
        return redirect('/index');
    }
}
