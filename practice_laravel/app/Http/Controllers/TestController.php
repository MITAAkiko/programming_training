<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;//getやpostを受け取れる？
use App\Models\Company;

class TestController extends Controller
{
    private $cmpMdl;
    public function __construct()
    {
        $this->cmpMdl = new Company;//モデル
    }
    public function func()
    {
        $datas = $this->cmpMdl->fetchData();
        $prefecture = config('config.PREFECTURES');
        return view('index', compact('datas', 'prefecture'));
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
        
    //     //初期値
    //     if (empty($get['order'])) {
    //         $get['order']=1;
    //     }
    //     //maxPageを取得する
    //     if (!empty($get['search'])) {
    //         $searched = '%'.$get['search'].'%' ;
    //         $cnt = $this->cmpMdl->fetchMaxPageSearched($searched);
    //         $maxPage = ceil($cnt['cnt']/10);
    //     } else {
    //         $cnt = $this->cmpMdl->fetchMaxPage();
    //         $maxPage = ceil($cnt['cnt']/10);
    //     }

    //     $page = 0;
    //     if (!empty($get['page'])) {
    //         $page= $get['page'];
    //         if ($page == '') {
    //             $page=1;
    //         }
    //     } else {
    //         $page = 1;
    //     }
    //     //最小値
    //     $page = max($page, 1);
    //     //最大値
    //     $page = min($page, $maxPage);
    //     //ページ
    //     $start = ($page - 1) * 10;

    //     //DBに接続する用意
    //     if (!empty($get['search'])) {//GETでおくる
    //         if (($get['order'])>0) {
    //             $searched = '%'.$get['search'].'%' ;
    //             $companies = $this->cmpMdl->fetchDataSearchedASC($searched, $start);
    //         } else {
    //             $searched = '%'.$get['search'].'%' ;
    //             $companies = $this->cmpMdl->fetchDataSearchedDESC($searched, $start);
    //         }
    //     } else {//検索なかった場合
    //         if (($get['order'])>0) {
    //             $companies = $this->cmpMdl->fetchDataASC($start);
    //         } else {
    //             $companies = $this->cmpMdl->fetchDataDESC($start);
    //         }
    //     }
    //     return [
    //         'companies' => $companies,
    //         'maxPage' => $maxPage,
    //         'page' => $page,
    //         'order' => $get['order']
    //     ];
    // }
}
