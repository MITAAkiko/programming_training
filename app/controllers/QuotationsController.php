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
    public function add($get, $post)
    {
                
        //エラーチェック
        function isError($err)
        {
            $nonerror=[
                'title' => '',
                'name' => '',
                'total' => '',
                'period' => '',
                'due' => '',
                'status' => ''
            ];
            return $err !== $nonerror;
        }
        //初期値
        $error = [
            'title' => '',
            'name' => '',
            'total' => '',
            'period' => '',
            'due' => '',
            'status' => ''
        ];
        $isError = '';

        //エラーについて
        if (!empty($post)) {
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
            if (($post['period'])==='') {
                $error['period']='blank';
            } elseif (!preg_match('/^[0-9]{8}$/', $post['period'])) {
                $error['period']='type';
            } elseif (strtotime($post['period'])===false) {
                $error['period']='check_date';
            }
            if (strtotime($post['period']) > strtotime($post['due'])) {
                $error['due']='time';
            } elseif (($post['due'])==='') {
                $error['due']='blank';
            } elseif (!preg_match('/^[0-9]{8}$/', $post['due'])) {
                $error['due']='type';
            } elseif (strtotime($post['due'])===false) {
                $error['due']='check_date';
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
            if (!$isError) {
                // $getids = $this->db->prepare('SELECT count(*)+1 AS getid FROM quotations WHERE company_id=?');//idを取得
                // $getids->bindParam(1, $get['id'], \PDO::PARAM_INT);
                // $getids->execute();
                // $getid = $getids->fetch();
                $getid = $this->quoMdl->addGetId($get);
                $quotate_id = str_pad($getid['getid'], 8, 0, STR_PAD_LEFT); // 8桁にする
                $no = $post['prefix'].'-q-'.$quotate_id;//見積番号

                $due = new \DateTime();
                $due = $due->format('Y-m-d');
                // $statement = $this->db->prepare('INSERT INTO quotations 
                //     SET company_id=?,no=?,
                //     title=?, total=?, validity_period=?, due_date=?, status=?, 
                //     created=NOW(),modified=NOW()');
                // $statement->bindParam(1, $get['id'], \PDO::PARAM_INT);
                // $statement->bindParam(2, $no, \PDO::PARAM_STR);
                // $statement->bindParam(3, $post['title'], \PDO::PARAM_STR);
                // $statement->bindParam(4, $post['total'], \PDO::PARAM_INT);
                // $statement->bindParam(5, $post['period'], \PDO::PARAM_INT);
                // $statement->bindValue(6, $due, \PDO::PARAM_STR);
                // $statement->bindParam(7, $post['status'], \PDO::PARAM_INT);
                // $statement->execute();
                $this->quoMdl->addData($get, $post, $due, $no);
                header('Location:./?id='.$post['return_id']);
                //exit();
            }
        }

        //$get['id']ない時戻す
        if (empty($get)) {
            header('Location:../companies');
            //exit();
        }
        //会社名取得
        if (!empty($get)) {
            // $companies = $db->prepare('SELECT company_name, prefix ,id
            //     FROM companies WHERE id=?');
            // $companies->bindParam(1, $get['id'], PDO::PARAM_INT);
            // $companies->execute();
            // $company = $companies->fetch();
            $company = $this->quoMdl->addGetCompanyName($get);
        }
        return [
            'error' => $error,
            'company' => $company,
            'isError' => $isError,
        ];
    }
}
