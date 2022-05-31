<?php
namespace App\Models;

//require_once('../../dbconnect.php');
class CompaniesModel
{
    private $db;
    public function __construct()
    {
        $this->db = new \PDO('mysql:dbname=programming_training;host=127.0.0.1;charset=utf8', 'root', 'P@ssw0rd');
    }

    public function getMaxPageSearched($get)
    {
        $searched = '%'.$get['search'].'%' ;
        $counts = $this->db->prepare('SELECT COUNT(*) AS cnt FROM companies WHERE deleted IS NULL AND (company_name LIKE ? OR manager_name LIKE ?)');
        $counts->bindParam(1, $searched, \PDO::PARAM_STR);
        $counts->bindParam(2, $searched, \PDO::PARAM_STR);
        $counts->execute();
        $cnt = $counts->fetch();
        return [ 'cnt' => $cnt ];
    }
    public function getMaxPage()
    {
        $counts = $this->db->prepare('SELECT COUNT(*) AS cnt FROM companies WHERE deleted IS NULL');
        $counts->execute();
        $cnt = $counts->fetch();
        return [ 'cnt' => $cnt ];
    }
    public function getDataSearchedASC($get, $start)
    {
        $searched = '%'.$get['search'].'%' ;
        $companies=$this->db->prepare('SELECT id, company_name, manager_name, phone_number, postal_code, prefecture_code, address, mail_address 
            FROM companies WHERE deleted IS NULL AND (company_name LIKE ? OR manager_name LIKE ?)
            ORDER BY id ASC LIMIT ?,10');
        $companies->bindParam(1, $searched, \PDO::PARAM_STR);
        $companies->bindParam(2, $searched, \PDO::PARAM_STR);
        $companies->bindParam(3, $start, \PDO::PARAM_INT);
        $companies->execute();
        return ['companies' => $companies];
    }
    public function getDataSearchedDESC($get, $start)
    {
        $searched = '%'.$get['search'].'%' ;
        $companies=$this->db->prepare('SELECT id, company_name, manager_name, phone_number, postal_code, prefecture_code, address, mail_address 
            FROM companies WHERE deleted IS NULL AND (company_name LIKE ? OR manager_name LIKE ?)
            ORDER BY id DESC LIMIT ?,10');
        $companies->bindParam(1, $searched, \PDO::PARAM_STR);
        $companies->bindParam(2, $searched, \PDO::PARAM_STR);
        $companies->bindParam(3, $start, \PDO::PARAM_INT);
        $companies->execute();
        return ['companies' => $companies];
    }
    public function getDataASC($start)
    {
        $companies=$this->db->prepare('SELECT id, company_name, manager_name, phone_number, postal_code, prefecture_code, address, mail_address 
            FROM companies WHERE deleted IS NULL ORDER BY id ASC LIMIT ?,10');
        $companies->bindParam(1, $start, \PDO::PARAM_INT);
        $companies->execute();
        return ['companies' => $companies];
    }
    public function getDataDESC($start)
    {
        $companies=$this->db->prepare('SELECT id, company_name, manager_name, phone_number, postal_code, prefecture_code, address, mail_address 
            FROM companies WHERE deleted IS NULL ORDER BY id DESC LIMIT ?,10');
        $companies->bindParam(1, $start, \PDO::PARAM_INT);
        $companies->execute();
        return ['companies' => $companies];
    }
}