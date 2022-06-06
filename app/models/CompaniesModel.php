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
    public function fetchMaxPageSearched($searched)
    {
        $counts = $this->db->prepare('SELECT COUNT(*) AS cnt FROM companies WHERE deleted IS NULL AND (company_name LIKE ? OR manager_name LIKE ?)');
        $counts->bindParam(1, $searched, \PDO::PARAM_STR);
        $counts->bindParam(2, $searched, \PDO::PARAM_STR);
        $counts->execute();
        $cnt = $counts->fetch();
        return $cnt;
    }
    public function fetchMaxPage()
    {
        $counts = $this->db->prepare('SELECT COUNT(*) AS cnt FROM companies WHERE deleted IS NULL');
        $counts->execute();
        $cnt = $counts->fetch();
        return $cnt;
    }
    public function fetchDataSearchedASC($searched, $start)
    {
        $companies=$this->db->prepare('SELECT id, company_name, manager_name, phone_number, postal_code, prefecture_code, address, mail_address 
            FROM companies WHERE deleted IS NULL AND (company_name LIKE ? OR manager_name LIKE ?)
            ORDER BY id ASC LIMIT ?,10');
        $companies->bindParam(1, $searched, \PDO::PARAM_STR);
        $companies->bindParam(2, $searched, \PDO::PARAM_STR);
        $companies->bindParam(3, $start, \PDO::PARAM_INT);
        $companies->execute();
        return $companies;
    }
    public function fetchDataSearchedDESC($searched, $start)
    {
        $companies=$this->db->prepare('SELECT id, company_name, manager_name, phone_number, postal_code, prefecture_code, address, mail_address 
            FROM companies WHERE deleted IS NULL AND (company_name LIKE ? OR manager_name LIKE ?)
            ORDER BY id DESC LIMIT ?,10');
        $companies->bindParam(1, $searched, \PDO::PARAM_STR);
        $companies->bindParam(2, $searched, \PDO::PARAM_STR);
        $companies->bindParam(3, $start, \PDO::PARAM_INT);
        $companies->execute();
        return  $companies;
    }
    public function fetchDataASC($start)
    {
        $companies=$this->db->prepare('SELECT id, company_name, manager_name, phone_number, postal_code, prefecture_code, address, mail_address 
            FROM companies WHERE deleted IS NULL ORDER BY id ASC LIMIT ?,10');
        $companies->bindParam(1, $start, \PDO::PARAM_INT);
        $companies->execute();
        return $companies;
    }
    public function fetchDataDESC($start)
    {
        $companies=$this->db->prepare('SELECT id, company_name, manager_name, phone_number, postal_code, prefecture_code, address, mail_address 
            FROM companies WHERE deleted IS NULL ORDER BY id DESC LIMIT ?,10');
        $companies->bindParam(1, $start, \PDO::PARAM_INT);
        $companies->execute();
        return $companies;
    }
    //add
    public function create($post)
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
    public function fetchDataById($id)
    {
        //DBに接続する用意
        $companies = $this->db ->prepare('SELECT id, company_name, manager_name, phone_number, postal_code, prefecture_code, address, mail_address, prefix  
            FROM companies WHERE id=?');
        $companies -> bindParam(1, $id, \PDO::PARAM_INT);
        $companies -> execute();
        $company = $companies -> fetch();
        return $company;
    }
    public function update($id, $post)
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
        $statement->bindParam(8, $id, \PDO::PARAM_INT);
        $statement->execute();
    }
    //delete
    public function delete($id)
    {
         $del = $this->db->prepare('UPDATE companies SET deleted=NOW() WHERE id=?');
         $del -> bindParam(1, $id, \PDO::PARAM_INT);
         $del -> execute();
    }
}
