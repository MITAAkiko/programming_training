<?php

namespace App\Http\Controllers;

// use App\Http\Requests\CompanyRequest;//バリデーションの設定
use Illuminate\Http\Request;//getやpostを受け取れる

//use App\Models\Invoice;//モデル

class InvoiceController extends Controller
{
    //private $invMdl;
    public function __construct()
    {
        //$this->invMdl = new Quotation;//モデルのインスタンス生成
    }
    public function index(Request $get)
    {
        return view('invoices/index');
    }
}
