<?php

namespace App\Http\Controllers;

// use App\Http\Requests\CompanyRequest;//バリデーションの設定
use Illuminate\Http\Request;//getやpostを受け取れる
use App\Models\Quotation;//モデル

class QuotationController extends Controller
{
    //private $quoMdl;
    public function __construct()
    {
        //$this->quoMdl = new Quotation;//モデルのインスタンス生成
    }
    public function index(Request $get)
    {
        return view('quotations/index');
    }
}
