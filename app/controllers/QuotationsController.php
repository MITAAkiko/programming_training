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
        if (!empty($get['page'])) {
            $page= $get['page'];
            if ($page == '') {
                $page=1;
            }
        }
        //最小値
        $page = max($page, 1);
        //最大ページを取得する(検索ありなしで分ける)
        if (!empty($get['search'])) {
            // $counts = $this->db->prepare('SELECT COUNT(*) AS cnt FROM quotations WHERE company_id=? AND deleted IS NULL AND status=?');
            // $counts -> bindParam(1, $get['id'], \PDO::PARAM_INT);
            // $counts -> bindParam(2, $get['search'], \PDO::PARAM_INT);
            // $counts -> execute();
            // $cnt = $counts->fetch();
            $cnt = $this->quoMdl->getMaxpageSearched($get);
            $maxPage = ceil($cnt['cnt']/10);
        } else {
            // $counts = $this->db->prepare('SELECT COUNT(*) AS cnt FROM quotations WHERE company_id=? AND deleted IS NULL');
            // $counts -> bindParam(1, $get['id'], \PDO::PARAM_INT);
            // $counts -> execute();
            // $cnt = $counts->fetch();
            $cnt = $this->quoMdl->getMaxpage($get);
            $maxPage = ceil($cnt['cnt']/10);
        }
        //最大値
        $maxPage = max($maxPage, 1);
        $page = min($page, $maxPage);

        // //ページ
        // $start = ($page - 1) * 10;

        //DBに接続する用意
        //絞り込みあり
        if (!empty($get['search'])) {
            // $quotations = $this->db -> prepare('SELECT  q.id, q.no, q.title, c.manager_name ,q.total, q.validity_period, q.due_date, q.status, c.company_name
            //     FROM companies c , quotations q WHERE c.id=? AND q.company_id = c.id AND q.deleted IS NULL AND q.status=? ORDER BY q.no ASC ');//LIMIT ?,10
            // $quotations -> bindParam(1, $get['id'], \PDO::PARAM_INT);
            // $quotations -> bindParam(2, $get['search'], \PDO::PARAM_INT);
            // //$quotations -> bindParam(3, $start, PDO::PARAM_INT);
            // $quotations -> execute();
            $quotations = $this->quoMdl->showDataSearched($get);
        } else {//絞り込みなし
            // $quotations = $this->db -> prepare('SELECT  q.id, q.no, q.title, c.manager_name ,q.total, q.validity_period, q.due_date, q.status, c.company_name
            //     FROM companies c , quotations q WHERE c.id=? AND q.company_id = c.id AND q.deleted IS NULL ORDER BY q.no ASC ');//LIMIT ?,10
            // $quotations -> bindParam(1, $get['id'], \PDO::PARAM_INT);
            // //$quotations -> bindParam(2, $start, PDO::PARAM_INT);
            // $quotations -> execute();
            $quotations = $this->quoMdl->showData($get);
        }

        //会社名を表示させる（見積がないときなど）
        // $companies = $this->db -> prepare('SELECT  company_name, id FROM companies WHERE id=? ');
        // $companies -> bindParam(1, $get['id'], \PDO::PARAM_INT);
        // $companies -> execute();
        // $company = $companies -> fetch();
        $company = $this->quoMdl->showCompanyName($get);

        /*計算式で並べた方
        $l=-1;
        foreach ($quotations as $quotation) {
            $quo[$l+=1] = [
                'no' => $quotation['no'],
                'title' => $quotation['title'],
                "manager" => $quotation['manager_name'],
                "total" => number_format($quotation['total']),
                "period" => str_replace('-', '/', $quotation['validity_period']),
                "due" => str_replace('-', '/', $quotation['due_date']),
                "status" => STATUSES[$quotation['status']],
                "id" => $quotation['id']
            ];
        }*/

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
