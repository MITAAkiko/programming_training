<?php
namespace App\Controllers;

require_once('../../config.php');
//モデルのファイルを読み込む
require_once('../../app/models/InvoicesModel.php');
use App\Models\InvoicesModel;

//リクエストのファイル読み込み
require_once('../../app/requests/Request.php');
require_once('../../app/requests/InvoicesRequest.php');
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
        if (!empty($get['search'])) {//絞り込みあり
            if (!empty($get['order2'])) {//日付で昇順降順指定あるか
                if (ORDER[$order2] === 'DESC') {
                    $invoices = $this->invMdl->fetchDataSearchedDayDESC($get, $start);
                } else {
                    $invoices = $this->invMdl->fetchDataSearchedDayASC($get, $start);
                }
            } else {//idで昇順降順指定あるか
                if (ORDER[$order] === 'DESC') {
                    $invoices = $this->invMdl->fetchDataSearchedDESC($get, $start);
                } else {
                    $invoices = $this->invMdl->fetchDataSearchedASC($get, $start);
                }
            }
        } else {//絞り込みなし
            if (!empty($get['order2'])) {//日付で昇順降順指定あるか
                if (ORDER[$order2] === 'DESC') {
                    $invoices = $this->invMdl->fetchDataDayDESCById($get['id'], $start);
                } else {
                    $invoices = $this->invMdl->fetchDataDayASCById($get['id'], $start);
                }
            } else {//idで昇順降順指定あるか
                if (ORDER[$order] === 'DESC') {
                    $invoices = $this->invMdl->fetchDataDESCById($get['id'], $start);
                } else {
                    $invoices = $this->invMdl->fetchDataASCById($get['id'], $start);
                }
            }
        }
        //会社名を表示させる（見積がないときなど）
        $company = $this->invMdl->fetchCompanyNameById($get['id']);
        //idのない人を返す
        if (empty($get['id']) || $get['id']=='') {
            header('Location:../');
        }
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
