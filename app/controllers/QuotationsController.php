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
        $check = $this->quoMdl->checkId($get['id']);
        if (!$check) {
            header('Location:../');
        }

        //初期値
        if (empty($get['order'])) {
            $get['order']=1;
        }
        $page = 1;

        //maxPage(検索ありなしで分ける)
        if (!empty($get['search'])) {
            $cnt = $this->quoMdl->fetchMaxpageSearched($get);
            $maxPage = ceil($cnt['cnt']/10);
        } else {
            $cnt = $this->quoMdl->fetchMaxpage($get['id']);
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
        //会社名を表示させる（見積がないときなど）
        $company = $this->quoMdl->fetchCompanyNameById($get['id']);
        
        //絞り込みあり
        if (!empty($get['search'])) {
            $quotations = $this->quoMdl->fetchDataSearched($get);
        } else {//絞り込みなし
            $quotations = $this->quoMdl->fetchDataById($get['id']);
        }
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

        //データ数が０のときのデータ表示準備。データがない時とあるときの処理
        if (empty($quo)) {
            $quoCount = 0;
            $quo = null;
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
        $check = $this->quoMdl->checkId($get['id']);
        if (!$check) {
            header('Location:../');
        }
        
        //会社名取得
        if (!empty($get)) {
            $company = $this->quoMdl->fetchCompanyNameById($get['id']);
        }

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
                $getid = $this->quoMdl->fetchId($get['id']);
                $quotateId = str_pad($getid['getid'], 8, 0, STR_PAD_LEFT); // 8桁にする
                $no = $post['prefix'].'-q-'.$quotateId;//見積番号

                $due = new \DateTime();
                $due = $due->format('Y-m-d');
                $this->quoMdl->create($get['id'], $post, $due, $no);
                header('Location:./?id='.$post['return_id']);
                //exit();
            }
        }
        return [
            'error' => $error,
            'company' => $company,
            'isError' => $isError,//記入欄の選択項目のみリセットされるため、メッセージ残す。
        ];
    }
    public function edit($get, $post)
    {
        //idがない時はindex.phpに返す
        $check = $this->quoMdl->checkId($get['cid']);
        $check_quotation_id = $this->quoMdl->checkQuotationId($get['id']);
        if (!$check || !$check_quotation_id) {
            header('Location:../');
        } else {
            $id = $get['id'];
            $cid = $get['cid'];
        }
        //DBに接続する
        //会社名
        $company = $this->quoMdl->fetchCompanyNameById($cid);
        //編集用
        $quotation = $this->quoMdl->fetchDataByQuotationId($id);
        //バリデーションチェック
        //エラーチェック
        function isError2($err)
        {
            $nonerror=[
                'title' => '',
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
            if (($post['due'])==='') {
                $error['due']='blank';
            } elseif (!preg_match('/^[0-9]{8}$/', $post['due'])) {
                $error['due']='type';
            } elseif (strtotime($post['period']) > strtotime($post['due'])) {
                $error['due']='time';
            } elseif (strtotime($post['due'])===false) {
                $error['due']='check_date';
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
                $this->quoMdl->edit($id, $post);
                header('Location:./?id='.$company['id']);
                //exit();
            }
        }
        return [
            'company' => $company,
            'quotation' => $quotation,
            'error' => $error,
            'isError' => $isError,
        ];
    }
    public function delete($id, $cid)
    {
        if (empty($id) || empty($cid)) {
            header('Location:./');
        } elseif ($id === '' || $cid==='') {
            header('Location:./');
        } else {
            //削除する
            $this->quoMdl->delete($id);
            header('Location:index.php?id='.$cid);
            //exit();
        }
    }
}
