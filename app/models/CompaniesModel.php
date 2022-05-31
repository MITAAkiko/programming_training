<?php
namespace App\Models;

class CompaniesModel
{
    private $db;
    public function __construct()
    {
        $this->db = new \PDO('mysql:dbname=programming_training;host=127.0.0.1;charset=utf8', 'root', 'P@ssw0rd');
    }
//index
    public function getMaxPageSearched($search)
    {
        $searched = '%'.$search.'%' ;
        $counts = $this->db->prepare('SELECT COUNT(*) AS cnt FROM companies WHERE deleted IS NULL AND (company_name LIKE ? OR manager_name LIKE ?)');
        $counts->bindParam(1, $searched, \PDO::PARAM_STR);
        $counts->bindParam(2, $searched, \PDO::PARAM_STR);
        $counts->execute();
        $cnt = $counts->fetch();
        return $cnt;
    }
    public function getMaxPage()
    {
        $counts = $this->db->prepare('SELECT COUNT(*) AS cnt FROM companies WHERE deleted IS NULL');
        $counts->execute();
        $cnt = $counts->fetch();
        return $cnt;
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
    //add
    public function addData($post)
    {
        $statement = $this->db->prepare('INSERT INTO companies SET company_name=?, manager_name=?,phone_number=?,postal_code=?,prefecture_code=?,address=?,mail_address=?,prefix=?,created=NOW(),modified=NOW()');
        $statement->bindParam(1, $post['name'], \PDO::PARAM_STR);
        $statement->bindParam(2, $post['manager'], \PDO::PARAM_STR);
        $statement->bindParam(3, $post['phone'], \PDO::PARAM_STR);
        $statement->bindParam(4, $post['postal_code'], \PDO::PARAM_STR);
        $statement->bindParam(5, $post['prefecture_code'], \PDO::PARAM_STR);
        $statement->bindParam(6, $post['address'], \PDO::PARAM_STR);
        $statement->bindParam(7, $post['email'], \PDO::PARAM_STR);
        $statement->bindParam(8, $post['prefix'], \PDO::PARAM_STR);
        $statement->execute();
    }
    //edit
    public function editShowData($get)
    {
        //DBに接続する用意
        $companies = $this->db ->prepare('SELECT id, company_name, manager_name, phone_number, postal_code, prefecture_code, address, mail_address, prefix  
            FROM companies WHERE id=?');
        $companies -> bindParam(1, $get['id'], \PDO::PARAM_INT);
        $companies -> execute();
        $company = $companies -> fetch();
        return $company;
    }
    public function editData($get, $post)
    {
        $statement = $this->db->prepare('UPDATE companies 
        SET company_name=?, manager_name=?,phone_number=?,
        postal_code=?,prefecture_code=?,address=?,
        mail_address=?,modified=NOW() WHERE id=?');
        $statement->bindParam(1, $post['name'], \PDO::PARAM_STR);
        $statement->bindParam(2, $post['manager'], \PDO::PARAM_STR);
        $statement->bindParam(3, $post['phone'], \PDO::PARAM_INT);
        $statement->bindParam(4, $post['postal_code'], \PDO::PARAM_INT);
        $statement->bindParam(5, $post['prefecture_code'], \PDO::PARAM_INT);
        $statement->bindParam(6, $post['address'], \PDO::PARAM_STR);
        $statement->bindParam(7, $post['email'], \PDO::PARAM_STR);
        $statement->bindParam(8, $get['id'], \PDO::PARAM_INT);
        $statement->execute();
    }
    //deete
}
