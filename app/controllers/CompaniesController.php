<?php
namespace App\Controllers;

//モデルのファイルを読み込む
 require_once('../app/models/CompaniesModel.php');
 use App\Models\CompaniesModel;

//リクエストのファイル読み込み
require_once('../app/requests/Request.php');
require_once('../app/requests/CompaniesAddRequest.php');
use App\Requests\CompaniesAddRequest;

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
        if (empty($get['order'])) {
            $get['order']=1;
        }
        //maxPageを取得する
        if (!empty($get['search'])) {
            $searched = '%'.$get['search'].'%' ;
            $cnt = $this->cmpMdl->fetchMaxPageSearched($searched);
            $maxPage = ceil($cnt['cnt']/10);
        } else {
            $cnt = $this->cmpMdl->fetchMaxPage();
            $maxPage = ceil($cnt['cnt']/10);
        }

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
        //最大値
        $page = min($page, $maxPage);
        //ページ
        $start = ($page - 1) * 10;

        //DBに接続する用意　ここ
        if (!empty($get['search'])) {//GETでおくる
            if (ORDER[$get['order']]==='DESC') {
                $searched = '%'.$get['search'].'%' ;
                $companies = $this->cmpMdl->fetchDataSearchedDESC($searched, $start);
            } else {
                $searched = '%'.$get['search'].'%' ;
                $companies = $this->cmpMdl->fetchDataSearchedASC($searched, $start);
            }
        } else {//検索なかった場合
            if (ORDER[$get['order']]==='DESC') {
                $companies = $this->cmpMdl->fetchDataDESC($start);
            } else {
                $companies = $this->cmpMdl->fetchDataASC($start);
            }
        }
        return [
            'companies' => $companies,
            'maxPage' => $maxPage,
            'page' => $page,
            'order' => $get['order']
        ];
    }
    public function add($post)
    {
        // //バリデーションチェック
        if (!empty($post)) {
            $this->cmpError = new CompaniesAddRequest;
            $isError = $this->cmpError->checkIsError($post);
            $error = $this->cmpError->getError();
        }
        //都道府県はエラーサイズに

        //エラーがない時にデータベースに登録する
        if (!empty($post)) {
            if (!$isError) {
                $this->cmpMdl->create($post);
                header('Location:./');
                //exit();
            } else {
                //エラーがあったとき、選択項目をもう一度選択してもらう
                $error['prefecture_code'] = 'error';
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
        
        // バリデーションチェック
        // エラーチェック
        function isError2($err)
        {
            $nonerror=[
                'name' => '',
                'manager' => '',
                'phone' => '',
                'postal_code' => '',
                'prefecture_code' => '',
                'address' => '',
                'email' => ''
            ];
            return $err !== $nonerror;
        }
        //初期値
        $error = [
            'name' => '',
            'manager' => '',
            'phone' => '',
            'postal_code' => '',
            'prefecture_code' => '',
            'address' => '',
            'email' => ''
        ];
        $isError = '';

        //エラーについて
        if (!empty($post)) {
            if (($post['name'])==='') {
                $error['name']='blank';
            } elseif (strlen($post['name'])>64) {
                $error['name']='long';
            }
            if (($post['manager'])==='') {
                $error['manager']='blank';
            } elseif (strlen($post['manager'])>32) {
                $error['manager']='long';
            }
            if (($post['phone'])==='') {
                $error['phone']='blank';
            } elseif (!preg_match('/^[0-9]+$/', $post['phone'])) { //空文字ダメの半角数値
                $error['phone']='type';
            } elseif (strlen($post['phone'])>11) {
                $error['phone']='long';
            }
            if (($post['postal_code'])==='') {
                $error['postal_code']='blank';
            } elseif (!preg_match("/^[0-9]+$/", $post['postal_code'])) { //空文字ダメの半角数値
                $error['postal_code']='type';
            } elseif (strlen($post['postal_code'])>7) {
                $error['postal_code']='long';
            }
            if (($post['prefecture_code'])==='') {
                $error['prefecture_code']='blank';
            } elseif (!preg_match("/^[0-9]+$/", $post['prefecture_code'])) { //空文字ダメの半角数値
                $error['prefecture_code']='type';
            } elseif (($post['prefecture_code'])>47 || ($post['prefecture_code'])<1) {
                $error['prefecture_code']='long47';
            }
            if (($post['address'])==='') {
                $error['address']='blank';
            } elseif (strlen($post['address'])>100) {
                $error['address']='long';
            }
            if (($post['email'])==='') {
                $error['email']='blank';
            } elseif (!preg_match("/^[a-zA-Z0-9_+-]+(.[a-zA-Z0-9_+-]+)*@([a-zA-Z0-9][a-zA-Z0-9-]*[a-zA-Z0-9]*\.)+[a-zA-Z]{2,}$/", $post['email'])) {
                $error['email']='type';
            } elseif (strlen($post['email'])>100) {
                $error['email']='long';
            }
        }

        //エラーがある.ファンクションそのまま使えないから変数に代入
        $isError = isError2($error);

        //エラーがない時にデータベースに登録する
        if (!empty($post)) {
            if (!$isError) {
                $this->cmpMdl->update($get['id'], $post);
                header('Location:./');
                //exit();
            }
        }
        return [
            'error' => $error ,
            'company' => $company,
        ];
    }
    public function delete($get)
    {
        if (empty($get)) {
            header('Location:./');
        } elseif ($get['id'] == '') {
            header('Location:./');
        } else {
            $id = $get['id'];
            $this->cmpMdl->delete($id);
        
            header('Location:index.php');
            //exit();
        }
    }
}
