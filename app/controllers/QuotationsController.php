<?php
namespace App\Controllers;

//モデルのファイルを読み込む
require_once('../../app/models/QuotationsModel.php');
use App\Models\QuotationsModel;

class QuotationController
{
    private $quoMdl;
    public function __construct()
    {
        $this->quoMdl = new QuotationsModel;
    }
    public function index($get)
    {
        //初期値
        if (empty($get['order'])) {
            $get['order']=1;
        }
        $page = 1;

        //maxPage(検索ありなしで分ける)
        if (!empty($get['search'])) {
            $cnt = $this->quoMdl->getMaxpageSearched($get);
            $maxPage = ceil($cnt['cnt']/10);
        } else {
            $cnt = $this->quoMdl->getMaxpage($get);
            $maxPage = ceil($cnt['cnt']/10);
        }
        $maxPage = max($maxPage, 1);
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
        $page = min($page, $maxPage);

        //DBに接続する用意
        //絞り込みあり
        if (!empty($get['search'])) {
            $quotations = $this->quoMdl->showDataSearched($get);
        } else {//絞り込みなし
            $quotations = $this->quoMdl->showData($get);
        }
        //会社名を表示させる（見積がないときなど）
        $company = $this->quoMdl->showCompanyName($get);
        //キーを用いた方（・・・as $key => $quotation){ $quo[$key]=[・・・]でも同様の結果
        foreach ($quotations as $quotation) {
            $quo[] = [
                'no' => $quotation['no'],
                'title' => $quotation['title'],
                "manager" => $quotation['manager_name'],
                "total" => number_format($quotation['total']),
                "period" => str_replace('-', '/', $quotation['validity_period']),
                "due" => str_replace('-', '/', $quotation['due_date']),
                "status" => STATUSES[$quotation['status']],
                "id" => $quotation['id']
            ];
        }
        //idのない人を返す
        if (empty($get['id']) || $get['id']=='') {
            header('Location:../companies/');
            //exit();
        }
        //データがない時とあるときの処理
        if (empty($quo)) {
            $quoCount = 0;
        } else {
            $quoCount = count($quo);
        }
        return [
            'company' => $company,
            'quoCount' => $quoCount,
            'page' => $page,
            'maxPage' => $maxPage,
            'quo' => $quo,
            'order' => $get['order'],
        ];
    }
}
