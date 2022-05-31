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
        if (!empty($get['page'])) {
            $page= $get['page'];
            if ($page == '') {
                $page=1;
            }
        }
        if (empty($get['order'])) {
            $get['order']=1;
        }
        //最小値
        $page = max($page, 1);
        //最後のページを取得する
        if (!empty($get['search'])) {
            // $counts = $db->prepare('SELECT COUNT(*) AS cnt FROM invoices WHERE company_id=? AND deleted IS NULL AND status=?');
            // $counts -> bindParam(1, $get['id'], PDO::PARAM_INT);
            // $counts -> bindParam(2, $get['search'], PDO::PARAM_INT);
            // $counts -> execute();
            // $cnt = $counts->fetch();
            $cnt = $this->invMdl->getMaxpageSearched($get);
            $maxPage = ceil($cnt['cnt']/10);
        } else {
            // $counts = $db->prepare('SELECT COUNT(*) AS cnt FROM invoices WHERE company_id=? AND deleted IS NULL');
            // $counts -> bindParam(1, $get['id'], PDO::PARAM_INT);
            // $counts -> execute();
            // $cnt = $counts->fetch();
            $cnt = $this->invMdl->getMaxpage($get);
            $maxPage = ceil($cnt['cnt']/10);
        }

        //最大値
        $maxPage = max($maxPage, 1);
        $page = min($page, $maxPage);

        //ページ
        $start = ($page - 1) * 10;

        //DBに接続する用意
        //絞り込みあり
        if (!empty($get['search'])) {
            if (($get['order'])>0) {
                // $invoices = $this->db -> prepare('SELECT   i.id, i.no, i.title, c.manager_name ,i.total, i.payment_deadline, i.date_of_issue, i.quotation_no, i.status, c.company_name
                //     FROM companies c , invoices i WHERE c.id=? AND i.company_id = c.id AND i.deleted IS NULL AND i.status=? ORDER BY i.no ASC LIMIT ?,10');
                // $invoices -> bindParam(1, $get['id'], \PDO::PARAM_INT);
                // $invoices -> bindParam(2, $get['search'], \PDO::PARAM_INT);
                // $invoices -> bindParam(3, $start, \PDO::PARAM_INT);
                // $invoices -> execute();
                $invoices = $this->invMdl->showDataSearchedASC($get, $start);
            } else {
                // $invoices = $db -> prepare('SELECT   i.id, i.no, i.title, c.manager_name ,i.total, i.payment_deadline, i.date_of_issue, i.quotation_no, i.status, c.company_name
                //     FROM companies c , invoices i WHERE c.id=? AND i.company_id = c.id AND i.deleted IS NULL AND i.status=? ORDER BY i.no DESC LIMIT ?,10');
                // $invoices -> bindParam(1, $get['id'], PDO::PARAM_INT);
                // $invoices -> bindParam(2, $get['search'], PDO::PARAM_INT);
                // $invoices -> bindParam(3, $start, PDO::PARAM_INT);
                // $invoices -> execute();
                $invoices = $this->invMdl->showDataSearchedDESC($get, $start);
            }
        } else {
            if (($get['order'])>0) {
                // $invoices = $db -> prepare('SELECT  i.id, i.no, i.title, c.manager_name ,i.total, i.payment_deadline, i.date_of_issue, i.quotation_no, i.status, c.company_name
                //     FROM companies c , invoices i WHERE c.id=? AND i.company_id = c.id AND i.deleted IS NULL ORDER BY i.no ASC LIMIT ?,10');
                // $invoices -> bindParam(1, $get['id'], PDO::PARAM_INT);
                // $invoices -> bindParam(2, $start, PDO::PARAM_INT);
                // $invoices -> execute();
                $invoices = $this->invMdl->showDataASC($get, $start);
            } else {
                // $invoices = $db -> prepare('SELECT  i.id, i.no, i.title, c.manager_name ,i.total, i.payment_deadline, i.date_of_issue, i.quotation_no, i.status, c.company_name
                //     FROM companies c , invoices i WHERE c.id=? AND i.company_id = c.id AND i.deleted IS NULL ORDER BY i.no DESC LIMIT ?,10');
                // $invoices -> bindParam(1, $get['id'], PDO::PARAM_INT);
                // $invoices -> bindParam(2, $start, PDO::PARAM_INT);
                // $invoices -> execute();
                $invoices = $this->invMdl->showDataDESC($get, $start);
            }
        }
        //会社名を表示させる（見積がないときなど）

        // $companies = $db -> prepare('SELECT  company_name, id FROM companies WHERE id=? AND deleted IS NULL');
        // $companies -> bindParam(1, $get['id'], PDO::PARAM_INT);
        // $companies -> execute();
        // $company = $companies -> fetch();
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
