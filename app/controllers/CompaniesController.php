<?php
namespace App\Controllers;

//モデルのファイルを読み込む
 require_once('../app/models/CompaniesModel.php');
 use App\Models\CompaniesModel;

class CompaniesController
{
    //Model\CompaniesModelにつなぐための変数
    private $cmpMdl;
    public function __construct()
    {
        $this->cmpMdl = new CompaniesModel;
    }
    public function index($get, $post = null)
    {
        
        //初期値
        if (empty($get['order'])) {
            $get['order']=1;
        }
        //maxPageを取得する
        if (!empty($get['search'])) {
            $cnt = $this->cmpMdl->getMaxPageSearched($get['search']);
            
            $maxPage = ceil($cnt['cnt']/10);
        } else {
            $cnt = $this->cmpMdl->getMaxPage();
            
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

        //DBに接続する用意
        if (!empty($get['search'])) {//GETでおくる
            if (($get['order'])>0) {
                $companies = $this->cmpMdl->getDataSearchedASC($get, $start);
            } else {
                $companies = $this->cmpMdl->getDataSearchedDESC($get, $start);
            }
        } else {//検索なかった場合
            if (($get['order'])>0) {
                $companies = $this->cmpMdl->getDataASC($start);
            } else {
                $companies = $this->cmpMdl->getDataDESC($start);
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
        //バリデーションチェック
        function isError($err)
        {
            $nonerror=[
                'name' => '',
                'manager' => '',
                'phone' => '',
                'postal_code' => '',
                'prefecture_code' => '',
                'address' => '',
                'email' => '',
                'prefix' => '',
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
            'email' => '',
            'prefix' => '',
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
            } elseif (($post['prefecture_code'])==="empty") {
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

            if (($post['prefix'])==='') {
                $error['prefix']='blank';
            } elseif (strlen($post['prefix'])>16) {
                $error['prefix']='long';
            } elseif (!preg_match("/^[0-9a-zA-Z]+$/", $post['prefix'])) {//半角英数字、空文字NG
                $error['prefix']='type';
            }
        }

        //エラーがある.ファンクションそのまま使えないから変数に代入
        $isError = isError($error);

        //エラーがない時にデータベースに登録する
        if (!empty($post)) {
            if (!$isError) {
                $this->cmpMdl->addData($post);
                header('Location:./');
                exit();
            }
        }
        return $error;
    }
    public function edit($get, $post)
    {

        //idない場合は戻る
        if (empty($get)) {
            header('Location:./');
        }
        $company = $this->cmpMdl->editShowData($get);
        
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
                $this->cmpMdl->editData($get, $post);
                header('Location:./');
                exit();
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
            $this->cmpMdl->deleteData($id);
        
            header('Location:index.php');
            exit();
        }
    }
}
