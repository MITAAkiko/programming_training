<?php
namespace App\Controllers;

//定数ファイル読み込み
require_once('../config.php');
//モデルのファイルを読み込む
 require_once(APP.'/models/CompaniesModel.php');
 use App\Models\CompaniesModel;

//リクエストのファイル読み込み
require_once(APP.'/requests/Request.php');
require_once(APP.'/requests/CompaniesRequest.php');
use App\Requests\CompaniesRequest;

class CompaniesController
{
    //Modelにつなぐための変数（クラス外からアクセスできないようprivate）
    private $cmpMdl;
    private $cmpError;
    public function __construct()
    {
        $this->cmpMdl = new CompaniesModel;
    }
    public function index($get)
    {
        //初期値
        //昇順降順
        $order = 'ASC';
        if (!empty($get['order'])) {
            $order = $get['order'];
        }
        //最大ページ数を取得する
        if (!empty($get['search'])) {
            $searched = '%' . addcslashes($get['search'], '%_\\') . '%';
            $cnt = $this->cmpMdl->fetchMaxPageSearched($searched);
            $maxPage = ceil($cnt['cnt']/10);//切り上げ
        } else {
            $cnt = $this->cmpMdl->fetchMaxPage();
            $maxPage = ceil($cnt['cnt']/10);
        }
        //ページ移動用
        $page = 0;
        if (!empty($get['page'])) {
            $page = $get['page'];
            $page = adjust_page($page, $maxPage);
        } else {
            $page = 1;
        }
        $start = ($page - 1) * 10;

        //DBに接続　検索・昇順降順
        //モデルの中でif文を使いたくなかったので、ASC/DESCで分けて2パターン書いた
        if (!empty($get['search'])) {//GETでおくる
            $searched = '%' . addcslashes($get['search'], '%_\\') . '%' ;
            if ($order === 'DESC') {
                $companies = $this->cmpMdl->fetchDataSearchedDESC($searched, $start);
            } else {
                $companies = $this->cmpMdl->fetchDataSearchedASC($searched, $start);
            }
        } else {//検索なかった場合
            if ($order === 'DESC') {
                $companies = $this->cmpMdl->fetchDataDESC($start);
            } else {
                $companies = $this->cmpMdl->fetchDataASC($start);
            }
        }
        return [
            'companies' => $companies,
            'maxPage' => $maxPage,
            'page' => $page,
            'order' => $order
        ];
    }
    public function add($post)
    {
        //バリデーションチェック
        if (!empty($post)) {
            $this->cmpError = new CompaniesRequest;
            $isError = $this->cmpError->checkIsError($post);
            $error = $this->cmpError->getError();
            //エラーがない時にデータベースに登録する
            if (!$isError) {
                $this->cmpMdl->create($post);
                header('Location:./');
            } else {//エラーがあったとき、選択項目をもう一度選択してもらう
                if (empty($error['prefecture_code'])) {
                    $error['prefecture_code'] = 'error';
                }
                return $error;
            }
        }
    }
    public function edit($get, $post)
    {
        //idない場合は戻る
        if (empty($get)) {
            header('Location:./');
        }
        $company = $this->cmpMdl->fetchDataById($get['id']);
        if (!$company) {//id:該当するデータがない場合、戻す
            header('Location:./');
        }
        //バリデーションチェック
        if (!empty($post)) {
            $this->cmpError = new CompaniesRequest;
            $isError = $this->cmpError->checkIsError($post);
            $error = $this->cmpError->getError();
            //エラーがない時にデータベースに登録する
            if (!$isError) {
                $this->cmpMdl->update($get['id'], $post);
                header('Location:./');
            } else {//エラーがあったとき、選択項目をもう一度選択してもらう
                if (empty($error['prefecture_code'])) {
                    $error['prefecture_code'] = 'error';
                }
                return [
                    'error' => $error,
                    'company' => $company,
                ];
            }
        } else {//編集前のデータ
            return [
                'error' => null,
                'company' => $company,
            ];
        }
    }
    public function delete($id)
    {
        if (empty($id) || $id === '') {
            header('Location:./');
        } elseif (!$this->cmpMdl->fetchDataById($id)) {
            header('Location:./');
        } else {
            $this->cmpMdl->delete($id);
            header('Location:index.php');
        }
    }
}
