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
        //if ($get->has('search')) {
            $search = $get->input('search');
        //}

        return view('index', compact('datas', 'prefecture', 'search'));
    }
    // //GET送る
        // public function get(){
        //     return view('sample');
        // }
     //GET受ける
        // public function getSearch(Request $get)
        // {
        //     $search = $get->input('search');
        //     //return view('sample')->with('searched', $search);
        //     return view('sample', compact('search'));
        // }
        // public function index($get, $post = null)
        // {

    public function add()
    {
        $prefecture = config('config.PREFECTURES');
        return view('add', compact('prefecture'));
    }
}
