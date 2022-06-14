<?php
namespace App\Controllers;

//モデルのファイルを読み込む
 require_once('../app/models/CompaniesModel.php');
 use App\Models\CompaniesModel;

//リクエストのファイル読み込み
require_once('../app/requests/Request.php');
require_once('../app/requests/CompaniesRequest.php');
use App\Requests\CompaniesRequest;

//定数ファイル読み込み
require_once('../config.php');

class CompaniesController
{
    //Model\CompaniesModelにつなぐための変数
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
            $maxPage = ceil($cnt['cnt']/10);
        } else {
            $cnt = $this->cmpMdl->fetchMaxPage();
            $maxPage = ceil($cnt['cnt']/10);
        }
        //ページ移動用
        $page = 0;
        if (!empty($get['page'])) {
            $page= $get['page'];
            if ($page == '') {
                $page=1;
            }
        } else {
            $page = 1;
        }
        //最小値
        $page = max($page, 1);
        //最大値（存在しないページを指定された場合）
        $page = min($page, $maxPage);
        //ページ
        $start = ($page - 1) * 10;

        //DBに接続　検索・昇順降順
        if (!empty($get['search'])) {//GETでおくる
            if ($order==='DESC') {
                $searched = '%' . addcslashes($get['search'], '%_\\') . '%' ;
                $companies = $this->cmpMdl->fetchDataSearchedDESC($searched, $start);
            } else {
                $searched = '%' . addcslashes($get['search'], '%_\\') . '%' ;
                $companies = $this->cmpMdl->fetchDataSearchedASC($searched, $start);
            }
        } else {//検索なかった場合
            if ($order==='DESC') {
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
                //exit();
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
                //exit();
            } else {//エラーがあったとき、選択項目をもう一度選択してもらう
                $error['prefecture_code'] = 'error';
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
        if (empty($id)) {
            header('Location:./');
        } elseif ($id === '') {
            header('Location:./');
        } else {
            $this->cmpMdl->delete($id);
            header('Location:index.php');
        }
    }
}
