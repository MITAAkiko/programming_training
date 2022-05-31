<?php
namespace App\Controllers;

 require_once('../app/models/CompaniesModel.php');
 use App\Models\CompaniesModel;

class CompaniesController
{

     private $cmpMdl;
    private $db;
    public function __construct()
    {
        $this->cmpMdl = new CompaniesModel;
    }
    
    public function index($get, $post = null)
    {
        
        //初期値
        if (empty($get['order'])) {
            $get['order']=1;
        }
        //maxPageを取得する
        if (!empty($get['search'])) {
            $res = $this->cmpMdl->getMaxPageSearched($get);
            $cnt = $res['cnt'];
            $maxPage = ceil($cnt['cnt']/10);
        } else {
            $res = $this->cmpMdl->getMaxPage();
            $cnt = $res['cnt'];
            $maxPage = ceil($cnt['cnt']/10);
        }

        $page = 0;
        if (!empty($get['page'])) {
            $page= $get['page'];
            if ($page == '') {
                $page=1;
            }
        } else {
            $page = 1;
        }
        //最小値
        $page = max($page, 1);
        //最大値
        $page = min($page, $maxPage);
        //ページ
        $start = ($page - 1) * 10;

        //DBに接続する用意
        if (!empty($get['search'])) {//GETでおくる
            if (($get['order'])>0) {
                $res = $this->cmpMdl->getDataSearchedASC($get, $start);
                $companies = $res['companies'];
            } else {
                $res = $this->cmpMdl->getDataSearchedDESC($get, $start);
                $companies = $res['companies'];
            }
        } else {//検索なかった場合
            if (($get['order'])>0) {
                $res = $this->cmpMdl->getDataASC($start);
                $companies = $res['companies'];
            } else {
                $res = $this->cmpMdl->getDataDESC($start);
                $companies = $res['companies'];
            }
        }
        return [
            'companies' => $companies,
            'maxPage' => $maxPage,
            'page' => $page,
            'order' => $get['order']
        ];
    }
}
