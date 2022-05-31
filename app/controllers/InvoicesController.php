<?php
namespace App\Controllers;

//モデルのファイルを読み込む
require_once('../../app/models/InvoicesModel.php');
use App\Models\InvoicesModel;

class InvoicesController
{
    //Model\CompaniesModelにつなぐための変数
    private $invMdl;
    public function __construct()
    {
        $this->invMdl = new InvoicesModel;
    }
    public function index($get, $post = null)
    {
        //初期値
        $page = 1;
        if (empty($get['order'])) {
            $get['order']=1;
        }
        //maxPage
        if (!empty($get['search'])) {
            $cnt = $this->invMdl->getMaxpageSearched($get);
            $maxPage = ceil($cnt['cnt']/10);
        } else {
            $cnt = $this->invMdl->getMaxpage($get);
            $maxPage = ceil($cnt['cnt']/10);
        }
        //page
        if (!empty($get['page'])) {
            $page= $get['page'];
            if ($page == '') {
                $page=1;
            }
        }
        //最小値
        $page = max($page, 1);
        //最大値
        $maxPage = max($maxPage, 1);
        $page = min($page, $maxPage);
        //ページ
        $start = ($page - 1) * 10;
        
        //DBに接続する用意
        //絞り込みあり
        if (!empty($get['search'])) {
            if (($get['order'])>0) {
                $invoices = $this->invMdl->showDataSearchedASC($get, $start);
            } else {
                $invoices = $this->invMdl->showDataSearchedDESC($get, $start);
            }
        } else {
            if (($get['order'])>0) {
                $invoices = $this->invMdl->showDataASC($get, $start);
            } else {
                $invoices = $this->invMdl->showDataDESC($get, $start);
            }
        }
        //会社名を表示させる（見積がないときなど）
        $company = $this->invMdl->showCompanyName($get);
        //idのない人を返す
        if (empty($get['id']) || $get['id']=='') {
            header('Location:../companies/');
            //exit();
        }
        return [
            'maxPage' => $maxPage,
            'invoices' => $invoices,
            'company' => $company,
            'page' => $page,
            'order' => $get['order']
        ];
    }
}
