<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;//バリデーションの設定
use Illuminate\Http\Request;//getやpostを受け取れる
use App\Models\Company;//モデル
use Illuminate\Support\Facades\Log;

class CompanyController extends Controller
{
    private $cmpMdl;
    public function __construct()
    {
        $this->cmpMdl = new Company;//モデルのインスタンス生成
    }
    public function index(Request $get)
    {
        $prefecture = config('config.PREFECTURES');
        $order = 'ASC';//初期設定
        $search = $get->input('search');
        $order = $get->input('order');

        if ($get->has('search')) {//検索あり
            $datas = $this->cmpMdl->fetchSearchedDatas($search, $order);
        } else {//検索なし
            $datas = $this->cmpMdl->fetchDatas($order);
        }
        return view('index', compact('datas', 'prefecture', 'search', 'order'));
    }
    public function add()
    {
        $prefecture = config('config.PREFECTURES');
        return view('add', compact('prefecture'));
    }
    public function addValidation(CompanyRequest $post)
    {
        $this->cmpMdl->create($post->safe()->all());
        return redirect('/index');//ただ画面に戻る場合はredirect
    }
    public function edit($id)
    {
        $data = $this->cmpMdl->fetchDataById($id);
        $prefecture = config('config.PREFECTURES');
        return view('edit', compact('prefecture', 'data'));
    }
    public function editValidation($id, CompanyRequest $post)
    {
        $this->cmpMdl->updateData($id, $post->safe()->all());
        return redirect('/index');
    }
    //普通にポストで削除する場合
    // public function delete(Request $post)
    // {
    //     $id = $post->input('id');
    //     $this->cmpMdl->deleteData($id);
    //     return redirect('/index');
    // }
    
    //@methodを使った場合
    public function destroy(Request $post)
    {
        if (!$this->cmpMdl->fetchDataById($post['id'])) {
            return redirect('/index');
        }
        $this->cmpMdl->deleteData($post['id']);
        return redirect('/index');
    }
}
