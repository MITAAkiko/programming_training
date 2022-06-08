<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;//getやpostを受け取れる？
use App\Models\Company;

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


    public function add()
    {
        $prefecture = config('config.PREFECTURES');
        return view('add', compact('prefecture'));
    }
}
