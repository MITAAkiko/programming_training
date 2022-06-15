<?php
namespace App\Controllers;

require_once('../../config.php');
//モデルのファイルを読み込む
require_once(APP.'/models/InvoicesModel.php');
use App\Models\InvoicesModel;

//リクエストのファイル読み込み
require_once(APP.'/requests/Request.php');
require_once(APP.'/requests/InvoicesRequest.php');
use App\Requests\InvoicesRequest;

class InvoicesController
{
    //Modelにつなぐための変数
    private $invMdl;
    private $invError;
    public function __construct()
    {
        $this->invMdl = new InvoicesModel;
    }
    public function index($get)
    {
        //idチェック
        $check = $this->invMdl->checkId($get['id']);
        if (!$check) {
            header('Location:../');
        }
        //ステータス正しいかチェック
        if (!empty($get['search']) && sttnum($get['search']) === null) {
            header('Location:./?id='.$get['id']);
        }
        //オーダー正しいか
        if (!empty($get['order']) && ordnum($get['order']) === null) {
            header('Location:./?id='.$get['id']);
        }
        //オーダー2正しいか
        if (!empty($get['order2']) && ordnum($get['order2']) === null) {
            header('Location:./?id='.$get['id']);
        }
        //昇順降順
        $page = 1;
        $order = 1;
        $order2 = -1;
        if (!empty($get['order'])) {
            $order = $get['order'];
        }
        if (!empty($get['order2'])) {
            $order2 = $get['order2'];
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
            $page = $get['page'];
            if ($page === '') {
                $page = 1;
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
        if (!empty($get['search'])) {//絞り込みあり
            if (!empty($get['order2'])) {//日付で昇順降順指定あるか
                if ($order2 === '-1') {
                    $invoices = $this->invMdl->fetchDataSearchedDayDESC($get, $start);
                } else {
                    $invoices = $this->invMdl->fetchDataSearchedDayASC($get, $start);
                }
            } else {//idで昇順降順指定あるか
                if ($order === '-1') {
                    $invoices = $this->invMdl->fetchDataSearchedDESC($get, $start);
                } else {
                    $invoices = $this->invMdl->fetchDataSearchedASC($get, $start);
                }
            }
        } else {//絞り込みなし
            if (!empty($get['order2'])) {//日付で昇順降順指定あるか
                if ($order2 === '-1') {
                    $invoices = $this->invMdl->fetchDataDayDESCById($get['id'], $start);
                } else {
                    $invoices = $this->invMdl->fetchDataDayASCById($get['id'], $start);
                }
            } else {//idで昇順降順指定あるか
                if ($order === '-1') {
                    $invoices = $this->invMdl->fetchDataDESCById($get['id'], $start);
                } else {
                    $invoices = $this->invMdl->fetchDataASCById($get['id'], $start);
                }
            }
        }
        //会社名を表示させる（見積がないときなど）
        $company = $this->invMdl->fetchCompanyNameById($get['id']);

        return [
            'maxPage' => $maxPage,
            'invoices' => $invoices,
            'company' => $company,
            'page' => $page,
            'order' => $order,
            'order2' => $order2,
        ];
    }
    public function add($get, $post)
    {
        //idチェック
        $check = $this->invMdl->checkId($get['id']);
        if (!$check) {
            header('Location:../');
        }
        //会社名取得
        $company = $this->invMdl->fetchCompanyNameById($get['id']);

        //バリデーションチェック
        if (!empty($post)) {
            $this->invError = new InvoicesRequest;
            $isError = $this->invError->checkIsError($post);
            $error = $this->invError->getError();
            //エラーがない時にデータベースに登録する
            if (!$isError) {
                $getid = $this->invMdl->fetchId($get['id']);//データの個数をカウントして＋１
                $invoiceId = str_pad($getid['getid'], 8, 0, STR_PAD_LEFT); // 8桁にする
                $no = $post['prefix'].'-i-'.$invoiceId;//請求番号
                //登録実行
                $this->invMdl->create($get['id'], $post, $no);
                header('Location:./?id='.h($post['return_id']));
            } else {//エラーがあったとき、選択項目をもう一度選択してもらう
                if (empty($error['status'])) {
                    $error['status'] = 'error';
                }
                return [
                    'company' => $company,
                    'isError' => $isError,
                    'error' => $error,
                ];
            }
        }
        return [//最初読み込んだ時
            'error' => null,
            'company' => $company,
            'isError' => null,
        ];
    }
    public function edit($get, $post)
    {
        //idがない時はindex.phpに返す
        $check = $this->invMdl->checkId($get['cid']);
        $checkInvoiceId = $this->invMdl->checkInvoiceId($get['id']);
        if (!$check || !$checkInvoiceId) {
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
        if (!empty($post)) {
            $this->invError = new InvoicesRequest;
            $isError = $this->invError->checkIsError($post);
            $error = $this->invError->getError();
            //エラーがない時にデータベースに登録する
            if (!$isError) {
                //登録実行
                $this->invMdl->update($id, $post);
                header('Location:./?id='.$company['id']);
            } else {//エラーがあったとき、選択項目をもう一度選択してもらう
                if (empty($error['status'])) {
                    $error['status'] = 'error';
                }
                return [
                    'invoice' => $invoice,
                    'company' => $company,
                    'isError' => $isError,
                    'error' => $error,
                ];
            }
        }
        return [
            'invoice' => $invoice,
            'company' => $company,
            'error' => null,
            'isError' => null,
        ];
    }
    public function delete($id, $cid)
    {
        if (empty($cid) || empty($id) || $id === '' || $cid === '') {
            header('Location:./');
        } elseif (!$this->invMdl->fetchDataById($id)) {
            header('Location:./');
        } else {
            //削除する
            $this->invMdl->delete($id);
            header('Location:index.php?id='.$cid);
        }
    }
}
