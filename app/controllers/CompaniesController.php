<?php
namespace App\Controllers;

 require_once('../app/models/CompaniesModel.php');
 use App\Models\CompaniesModel;

//require_once('../../dbconnect.php');
class CompaniesController
{

     private $cmpMdl;
    private $db;
    public function __construct()
    {
        $this->cmpMdl = new CompaniesModel;
        //$this->db = new \PDO('mysql:dbname=programming_training;host=127.0.0.1;charset=utf8', 'root', 'P@ssw0rd');
    }
    
    public function index($get, $post = null)
    {
        
        //初期値
        if (empty($get['order'])) {
            $get['order']=1;
        }
        //maxPageを取得する
        if (!empty($get['search'])) {
            // $searched = '%'.$get['search'].'%' ;
            // $counts = $this->db->prepare('SELECT COUNT(*) AS cnt FROM companies WHERE deleted IS NULL AND (company_name LIKE ? OR manager_name LIKE ?)');
            // $counts->bindParam(1, $searched, \PDO::PARAM_STR);
            // $counts->bindParam(2, $searched, \PDO::PARAM_STR);
            // $counts->execute();
            // $cnt = $counts->fetch();
            
            $res = $this->cmpMdl->getMaxPageSearched($get['search']);
            $cnt = $res['cnt'];
            $maxPage = ceil($cnt['cnt']/10);
        } else {
            // $counts = $this->db->prepare('SELECT COUNT(*) AS cnt FROM companies WHERE deleted IS NULL');
            // $counts->execute();
            // $cnt = $counts->fetch();
            $res = $this->cmpMdl->getMaxPage();
            $cnt = $res['cnt'];
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
                // $searched = '%'.$get['search'].'%' ;
                // $companies=$this->db->prepare('SELECT id, company_name, manager_name, phone_number, postal_code, prefecture_code, address, mail_address 
                //     FROM companies WHERE deleted IS NULL AND (company_name LIKE ? OR manager_name LIKE ?)
                //     ORDER BY id ASC LIMIT ?,10');
                // $companies->bindParam(1, $searched, \PDO::PARAM_STR);
                // $companies->bindParam(2, $searched, \PDO::PARAM_STR);
                // $companies->bindParam(3, $start, \PDO::PARAM_INT);
                // $companies->execute();
                $res = $this->cmpMdl->getDataSearchedASC($get, $start);
                $companies = $res['companies'];
            } else {
                // $searched = '%'.$get['search'].'%' ;
                // $companies=$this->db->prepare('SELECT id, company_name, manager_name, phone_number, postal_code, prefecture_code, address, mail_address 
                //     FROM companies WHERE deleted IS NULL AND (company_name LIKE ? OR manager_name LIKE ?)
                //     ORDER BY id DESC LIMIT ?,10');
                // $companies->bindParam(1, $searched, \PDO::PARAM_STR);
                // $companies->bindParam(2, $searched, \PDO::PARAM_STR);
                // $companies->bindParam(3, $start, \PDO::PARAM_INT);
                // $companies->execute();
                $res = $this->cmpMdl->getDataSearchedDESC($get, $start);
                $companies = $res['companies'];
            }
        } else {//検索なかった場合
            if (($get['order'])>0) {
                // $companies=$this->db->prepare('SELECT id, company_name, manager_name, phone_number, postal_code, prefecture_code, address, mail_address 
                //     FROM companies WHERE deleted IS NULL ORDER BY id ASC LIMIT ?,10');
                // $companies->bindParam(1, $start, \PDO::PARAM_INT);
                // $companies->execute();
                $res = $this->cmpMdl->getDataASC($start);
                $companies = $res['companies'];
            } else {
                // $companies=$this->db->prepare('SELECT id, company_name, manager_name, phone_number, postal_code, prefecture_code, address, mail_address 
                //     FROM companies WHERE deleted IS NULL ORDER BY id DESC LIMIT ?,10');
                // $companies->bindParam(1, $start, \PDO::PARAM_INT);
                // $companies->execute();
                $res = $this->cmpMdl->getDataDESC($start);
                $companies = $res['companies'];
            }
        }

        return [
            'companies' => $companies,
            'maxPage' => $maxPage,
            'page' => $page,
            'order' => $get['order']
        ];
    }
}

// class CompaniesController
// {
//     public function hel()
//     {
//         echo 'こんにちは';
//     }
// }
