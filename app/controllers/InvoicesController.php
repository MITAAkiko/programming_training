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
        $check = $this->invMdl->checkId($get['id']);
        if (!$check) {
            header('Location:../');
        }
        //初期値
        $page = 1;
        if (empty($get['order'])) {
            $get['order']=1;
        }
        //maxPage
        if (!empty($get['search'])) {
            $cnt = $this->invMdl->fetchMaxpageSearched($get);
            $maxPage = ceil($cnt['cnt']/10);
        } else {
            $cnt = $this->invMdl->fetchMaxpageById($get['id']);
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
                $invoices = $this->invMdl->fetchDataSearchedASC($get, $start);
            } else {
                $invoices = $this->invMdl->fetchDataSearchedDESC($get, $start);
            }
        } else {
            if (($get['order'])>0) {
                $invoices = $this->invMdl->fetchDataASCById($get['id'], $start);
            } else {
                $invoices = $this->invMdl->fetchDataDESCById($get['id'], $start);
            }
        }
        //会社名を表示させる（見積がないときなど）
        $company = $this->invMdl->fetchCompanyNameById($get['id']);
        //idのない人を返す
        if (empty($get['id']) || $get['id']=='') {
            header('Location:../');
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
        $check = $this->invMdl->checkId($get['id']);
        if (!$check) {
            header('Location:../');
        }
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
            if (!$isError) {
                //id取得
                $getid = $this->invMdl->fetchId($get['id']);
                $invoiceId = str_pad($getid['getid'], 8, 0, STR_PAD_LEFT); // 8桁にする
                $no = $post['prefix'].'-i-'.$invoiceId;//請求番号
                //登録実行
                $this->invMdl->create($get['id'], $post, $no);
                header('Location:./?id='.h($post['return_id']));
            }
        }

        //$get['id']ない時戻す
        if (empty($get)) {
            header('Location:../companies/');
            // exit();
        }
        //会社名取得
            $company = $this->invMdl->fetchCompanyNameById($get['id']);
        return [
            'error' => $error,
            'company' => $company,
            'isError' => $isError,
            'check' => $check
        ];
    }
    public function edit($get, $post)
    {
        //idがない時はindex.phpに返す
        $check = $this->invMdl->checkId($get['cid']);
        $check_invoice_id = $this->invMdl->checkInvoiceId($get['id']);
        if (!$check || !$check_invoice_id) {
            header('Location:../');
        } else {
            $id = $get['id'];
            $cid = $get['cid'];
        }
        //DBに接続する
        //会社名
        $company = $this->invMdl->fetchCompanyNameById($cid);
        //編集用
        $invoice = $this->invMdl->fetchDataById($id);

        //バリデーションチェック
        //エラーチェック
        function isError2($err)
        {
            $nonerror=[
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
            'title' => '',
            'total' => '',
            'pay' => '',
            'date' => '',
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
            if (($post['pay'])==='') {
                $error['pay']='blank';
            } elseif (!preg_match('/^[0-9]{8}$/', $post['pay'])) {
                $error['pay']='type';
            } elseif (strtotime($post['pay']) < strtotime($post['date'])) {
                $error['pay']='time';
            } elseif (strtotime($post['pay'])===false) {
                $error['pay']='check_date';
            }
            if (($post['date'])==='') {
                $error['date']='blank';
            } elseif (!preg_match('/^[0-9]{8}$/', $post['date'])) {
                $error['date']='type';
            } elseif (strtotime($post['date'])===false) {
                $error['date']='check_date';
            }
            if (($post['status'])==='') {
                $error['status']='blank';
            } elseif (!preg_match("/^[0-9]+$/", $post['status'])) { //空文字ダメの半角数値
                $error['status']='type';
            } elseif (strlen($post['status'])>1) {
                $error['status']='long';
            }
        }

        //エラーがある.ファンクションそのまま使えないから変数に代入
        $isError = isError2($error);
        //エラーがあったときに状態をもう一度選択する促し
        if ($isError) {
            $error['status']='iserr';
        }
        //エラーがない時にデータベースに登録する
        if (!empty($post)) {
            if (!$isError) {
                $this->invMdl->update($get['id'], $post);
                header('Location:./?id='.$company['id']);
                //exit();
            }
        }
        return [
            'invoice' => $invoice,
            'error' => $error,
            'company' => $company,
        ];
    }
    public function delete($id, $cid)
    {
        if (empty($cid) || empty($id)) {
            header('Location:./');
        } elseif ($id === '' || $cid === '') {
            header('Location:./');
        } else {
            //削除する
            $this->invMdl->delete($id);
            header('Location:index.php?id='.$cid);
            //exit();
        }
    }
}
