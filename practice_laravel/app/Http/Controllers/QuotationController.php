<?php

namespace App\Http\Controllers;

// use App\Http\Requests\CompanyRequest;//バリデーションの設定

use App\Http\Requests\QuotationRequest;
use Illuminate\Http\Request;//getやpostを受け取れる
use App\Models\Quotation;//モデル

class QuotationController extends Controller
{
    private $quoMdl;
    public function __construct()
    {
        $this->quoMdl = new Quotation;//モデルのインスタンス生成
    }
    public function index(Request $get)
    {
        $status = config('config.STATUSES');
        $order = 'ASC';//初期設定
        $search = null;
        $search = $get->input('search');
        $order = $get->input('order');
        $cid = $get->input('id');
        $company = $this->quoMdl->fetchCompanyName($cid);
        if ($get->has('search') && $get['search'] !== null) {//検索あり
            $quotations = $this->quoMdl->fetchDataSearched($get, $order);
        } else {//検索なし
            $quotations = $this->quoMdl->fetchData($cid, $order);
        }
        return view('quotations/index', compact('quotations', 'company', 'status', 'search', 'order'));
    }
    public function add(Request $get)
    {
        $status = config('config.STATUSES');
        $cid = $get->input('id');
        $company = $this->quoMdl->fetchCompanyName($cid);
        return view('quotations/add', compact('company', 'status'));
    }
    public function addValidation(QuotationRequest $post)
    {
        $getcount = $this->quoMdl->fetchId($post['cid']);//データの個数をカウントして＋１
        $quoId = str_pad($getcount, 8, 0, STR_PAD_LEFT);// 8桁にする
        $quono = $post['prefix'].'-q-'.$quoId;
        $this->quoMdl->create($post['cid'], $quono, $post->safe()->all());
        return redirect('quotations/index?id='.$post['cid']);
    }
    public function edit(Request $get)
    {
        $status = config('config.STATUSES');
        $cid = $get->input('cid');
        $id = $get->input('id');
        $company = $this->quoMdl->fetchCompanyName($cid);
        $data = $this->quoMdl->fetchDataById($cid, $id);
        return view('quotations/edit', compact('company', 'status', 'data'));
    }
    public function editValidation(QuotationRequest $post)
    {
        $this->quoMdl->updateData($post['id'], $post->safe()->all());//post→は、ポストからバリデーションされたものをとってくる
        return redirect('quotations/index?id='.$post['cid']);
    }
    public function delete(Request $post)
    {
        $id = $post->input('id');
        $this->quoMdl->deleteData($id);
        return redirect('quotations/index?id='.$post['cid']);
    }
}
