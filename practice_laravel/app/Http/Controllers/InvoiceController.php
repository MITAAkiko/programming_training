<?php

namespace App\Http\Controllers;

// use App\Http\Requests\CompanyRequest;//バリデーションの設定

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
        $cid = $get -> input('id');
        $company = $this -> invMdl -> fetchCompanyName($cid);
        if ($get->has('search')) {//検索あり
            $invoices = $this -> invMdl -> fetchDataSearched($get, $order);
        } else {//検索なし
            $invoices = $this -> invMdl -> fetchData($cid, $order);
        }
        return view('invoices/index', compact('invoices', 'company', 'status', 'search', 'order'));
    }
}
