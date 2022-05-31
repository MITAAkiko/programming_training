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
    public function add($get, $post)
    {
                //エラーチェック
        function isError($err)
        {
            $nonerror=[
                'quo' => '',
                'title' => '',
                'total' => '',
                'pay' => '',
                'date' => '',
                'status' => ''
            ];
            return $err !== $nonerror;
        }
        //初期値
        $error = [
            'quo' => '',
            'title' => '',
            'total' => '',
            'pay' => '',
            'date' => '',
            'status' => ''
        ];
        $isError = '';

        //エラーについて
        if (!empty($post)) {
            if (!preg_match("/^[0-9a-zA-Z]+$/", $post['quo'])) { //空文字ダメの半角数値
                $error['quo']='type';
            }
            if (($post['quo'])==='') {
                $error['quo']='blank';
            } elseif (strlen($post['quo'])>100) {
                $error['quo']='long';
            }
            if (($post['title'])==='') {
                $error['title']='blank';
            } elseif (strlen($post['title'])>64) {
                $error['title']='long';
            }
            if (($post['total'])==='') {
                $error['total']='blank';
            } elseif (!preg_match('/^[0-9]+$/', $post['total'])) { //空文字ダメの半角数値
                $error['total']='type';
            } elseif (strlen($post['total'])>10) {
                $error['total']='long';
            }
            if (($post['pay'])==='') {
                $error['pay']='blank';
            } elseif (!preg_match('/^[0-9]{8}$/', $post['pay'])) {
                $error['pay']='type';
            } elseif (strtotime($post['pay'])===false) {
                $error['pay']='check_date';
            } elseif (strtotime($post['pay']) < strtotime($post['date'])) {
                $error['pay']='time';
            }

            if (($post['date'])==='') {
                $error['date']='blank';
            } elseif (!preg_match('/^[0-9]{8}$/', $post['date'])) {
                $error['date']='type';
            } elseif (strtotime($post['date'])===false) {
                $error['date']='check_date';
            }
            if (!preg_match("/^[0-9]+$/", $post['status'])) { //空文字ダメの半角数値
                $error['status']='type';
            } elseif (strlen($post['status'])>1) {
                $error['status']='long';
            } elseif (($post['status'])==='') {
                $error['status']='blank';
            }
        }

        //エラーがある.ファンクションそのまま使えないから変数に代入
        $isError = isError($error);

        //エラーがない時にデータベースに登録する
        if (!empty($post)) {
            //var_dump($post);exit();
            if (!$isError) {
                // $getids = $db->prepare('SELECT count(*)+1 AS getid FROM invoices WHERE company_id=?');//idを取得
                // $getids->bindParam(1, $get['id'], PDO::PARAM_INT);
                // $getids->execute();
                // $getid = $getids->fetch();
                $getid = $this->invMdl->addGetId($get);
                $invoice_id = str_pad($getid['getid'], 8, 0, STR_PAD_LEFT); // 8桁にする
                $no = $post['prefix'].'-i-'.$invoice_id;//請求番号

                // $statement = $db->prepare('INSERT INTO invoices 
                //     SET company_id=?,no=?,
                //     title=?, total=?, payment_deadline=?, date_of_issue=?, quotation_no=?, status=?, 
                //     created=NOW(),modified=NOW()');
                // $statement->bindParam(1, $get['id'], PDO::PARAM_INT);
                // $statement->bindParam(2, $no, PDO::PARAM_STR);
                // $statement->bindParam(3, $post['title'], PDO::PARAM_STR);
                // $statement->bindParam(4, $post['total'], PDO::PARAM_INT);
                // $statement->bindParam(5, $post['pay'], PDO::PARAM_INT);
                // $statement->bindParam(6, $post['date'], PDO::PARAM_INT);
                // $statement->bindParam(7, $post['quo'], PDO::PARAM_STR);
                // $statement->bindParam(8, $post['status'], PDO::PARAM_INT);
                // $statement->execute();
                $this->invMdl->addData($get, $post, $no);
                header('Location:./?id='.h($post['return_id']));
            }
        }

        //$get['id']ない時戻す
        if (empty($get)) {
            header('Location:../companies/');
            // exit();
        }
        //会社名取得
        if (!empty($get)) {//いらない？
            // $companies = $db->prepare('SELECT company_name, prefix ,id
            //     FROM companies WHERE id=?');
            // $companies->bindParam(1, $get['id'], PDO::PARAM_INT);
            // $companies->execute();
            // $company = $companies->fetch();
            $company = $this->invMdl->addGetCompanyName($get);
        }
        return [
            'error' => $error,
            'company' => $company,
            'isError' => $isError,
        ];
    }
}
