<?php
namespace App\Controllers;

//モデルのファイルを読み込む
require_once('../../app/models/QuotationsModel.php');
use App\Models\QuotationsModel;

require_once('../../app/requests/Request.php');
require_once('../../app/requests/QuotationsRequest.php');
use App\Requests\QuotationsRequest;

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
        $order = 1;
        if (!empty($get['order'])) {
            $order = $get['order'];
        }
        $order2 = 1;
        if (!empty($get['order2'])) {
            $order2 = $get['order2'];
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
            if (!empty($get['order2'])) {
                $quotations = $this->quoMdl->fetchDataSearchedDay($get);
            } else {
                $quotations = $this->quoMdl->fetchDataSearched($get);
            }
        } else {//絞り込みなし
            if (!empty($get['order2'])) {
                $quotations = $this->quoMdl->fetchDataDayById($get['id']);
            } else {
                $quotations = $this->quoMdl->fetchDataById($get['id']);
            }
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
            'order' => $order,
            'order2' => $order2,
        ];
    }
    public function add($get, $post)
    {
        $check = $this->quoMdl->checkId($get['id']);
        if (!$check) {
            header('Location:../');
        }
        //会社名取得
        $company = $this->quoMdl->fetchCompanyNameById($get['id']);
        //バリデーションチェック
        if (!empty($post)) {
            $this->quoError = new QuotationsRequest;
            $isError = $this->quoError->checkIsError($post);
            $error = $this->quoError->getError();
            //エラーがない時にデータベースに登録する
            if (!$isError) {
                $getid = $this->quoMdl->fetchId($get['id']);
                $quotateId = str_pad($getid['getid'], 8, 0, STR_PAD_LEFT); // 8桁にする
                $no = $post['prefix'].'-q-'.$quotateId;//見積番号
                $this->quoMdl->create($get['id'], $post, $no);
                header('Location:./?id='.$post['return_id']);
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
        $check = $this->quoMdl->checkId($get['cid']);
        $checkQuotationId = $this->quoMdl->checkQuotationId($get['id']);
        if (!$check || !$checkQuotationId) {
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
        if (!empty($post)) {
            $this->quoError = new QuotationsRequest;
            $isError = $this->quoError->checkIsError($post);
            $error = $this->quoError->getError();
            //エラーがない時にデータベースに登録する
            if (!$isError) {
                $this->quoMdl->edit($id, $post);
                header('Location:./?id='.$company['id']);
            } else {//エラーがあったとき、選択項目をもう一度選択してもらう
                if (empty($error['status'])) {
                    $error['status'] = 'error';
                }
                return [
                    'company' => $company,
                    'quotation' => $quotation,
                    'isError' => $isError,
                    'error' => $error,
                ];
            }
        }
        return [
            'company' => $company,
            'quotation' => $quotation,
            'error' => null,
            'isError' => null,
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
