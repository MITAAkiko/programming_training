<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceRequest;//バリデーションの設定
use App\Models\Invoice;
use Illuminate\Http\Request;//getやpostを受け取れる

class InvoiceController extends Controller
{
    private $invMdl;
    public function __construct()
    {
        $this->invMdl = new Invoice();//モデルのインスタンス生成
    }
    public function index(Request $get)
    {
        $status = config('config.STATUSES');
        $order = 'ASC';//初期設定
        $search = null;
        $search = $get->input('search');
        $order = $get->input('order');
        $cid = $get->input('id');
        $company = $this->invMdl->fetchCompanyName($cid);
        if ($get->has('search') && $get['search'] !== null) {//検索あり
            $invoices = $this->invMdl->fetchDataSearched($get, $order);
        } else {//検索なし
            $invoices = $this->invMdl->fetchData($cid, $order);
        }
        return view('invoices/index', compact('invoices', 'company', 'status', 'search', 'order'));
    }
    public function add(Request $get)
    {
        $status = config('config.STATUSES');
        $cid = $get->input('id');
        $company = $this->invMdl->fetchCompanyName($cid);
        return view('invoices/add', compact('company', 'status'));
    }

    public function addValidation(InvoiceRequest $post)
    {
        $getcount = $this->invMdl->fetchId($post['cid']);//データの個数をカウントして＋１
        $invoiceId = str_pad($getcount, 8, 0, STR_PAD_LEFT); // 8桁にする
        $no = $post['prefix'].'-i-'.$invoiceId;//請求番号
        $this->invMdl->create($post['cid'], $no, $post->safe()->all());//post→は、ポストからバリデーションされたものをとってくる
        return redirect('invoices/index?id='.$post['cid']);
    }
    public function edit(Request $get)
    {
        $status = config('config.STATUSES');
        $cid = $get->input('cid');
        $id = $get->input('id');
        $company = $this->invMdl->fetchCompanyName($cid);
        $data = $this->invMdl->fetchDataById($id);
        return view('invoices/edit', compact('company', 'status', 'data'));
    }
    public function editValidation(InvoiceRequest $post)
    {
        $this->invMdl->updateData($post['cid'], $post['id'], $post->safe()->all());//post→は、ポストからバリデーションされたものをとってくる
        return redirect('invoices/index?id='.$post['cid']);
    }
    public function delete(Request $post)
    {
        $id = $post->input('id');
        if (!$this->invMdl->fetchDataById($id)) {
            return redirect('invoices/index?id='.$post['cid']);
        }
        $this->invMdl->deleteData($id);
        return redirect('invoices/index?id='.$post['cid']);
    }
}
