<?php

namespace App\Http\Controllers;

// use App\Http\Requests\CompanyRequest;//バリデーションの設定
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
}
