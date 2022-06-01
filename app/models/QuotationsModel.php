<?php
namespace App\Models;

class QuotationsModel
{
    private $db;
    public function __construct()
    {
        $this->db = new \PDO('mysql:dbname=programming_training;host=127.0.0.1;charset=utf8', 'root', 'P@ssw0rd');
    }
    //index
    public function getMaxpageSearched($get)
    {
        $counts = $this->db->prepare('SELECT COUNT(*) AS cnt FROM quotations WHERE company_id=? AND deleted IS NULL AND status=?');
        $counts -> bindParam(1, $get['id'], \PDO::PARAM_INT);
        $counts -> bindParam(2, $get['search'], \PDO::PARAM_INT);
        $counts -> execute();
        $cnt = $counts->fetch();
        return $cnt;
    }
    public function getMaxpage($get)
    {
        $counts = $this->db->prepare('SELECT COUNT(*) AS cnt FROM quotations WHERE company_id=? AND deleted IS NULL');
        $counts -> bindParam(1, $get['id'], \PDO::PARAM_INT);
        $counts -> execute();
        $cnt = $counts->fetch();
        return $cnt;
    }
    public function showDataSearched($get)
    {
        $quotations = $this->db -> prepare('SELECT  q.id, q.no, q.title, c.manager_name ,q.total, q.validity_period, q.due_date, q.status, c.company_name
            FROM companies c , quotations q WHERE c.id=? AND q.company_id = c.id AND q.deleted IS NULL AND q.status=? ORDER BY q.no ASC ');//LIMIT ?,10
        $quotations -> bindParam(1, $get['id'], \PDO::PARAM_INT);
        $quotations -> bindParam(2, $get['search'], \PDO::PARAM_INT);
        $quotations -> execute();
        return $quotations;
    }
    public function showData($get)
    {
        $quotations = $this->db -> prepare('SELECT  q.id, q.no, q.title, c.manager_name ,q.total, q.validity_period, q.due_date, q.status, c.company_name
            FROM companies c , quotations q WHERE c.id=? AND q.company_id = c.id AND q.deleted IS NULL ORDER BY q.no ASC ');//LIMIT ?,10
        $quotations -> bindParam(1, $get['id'], \PDO::PARAM_INT);
        $quotations -> execute();
        return $quotations;
    }
    public function showCompanyName($get)
    {
        $companies = $this->db -> prepare('SELECT  company_name, id FROM companies WHERE id=? ');
        $companies -> bindParam(1, $get['id'], \PDO::PARAM_INT);
        $companies -> execute();
        $company = $companies -> fetch();
        return $company;
    }
    //add
    public function addGetId($get)
    {
        $getids = $this->db->prepare('SELECT count(*)+1 AS getid FROM quotations WHERE company_id=?');//idを取得
        $getids->bindParam(1, $get['id'], \PDO::PARAM_INT);
        $getids->execute();
        $getid = $getids->fetch();
        return $getid;
    }
    public function addData($get, $post, $due, $no)
    {
        $statement = $this->db->prepare('INSERT INTO quotations 
            SET company_id=?,no=?,
            title=?, total=?, validity_period=?, due_date=?, status=?, 
            created=NOW(),modified=NOW()');
        $statement->bindParam(1, $get['id'], \PDO::PARAM_INT);
        $statement->bindParam(2, $no, \PDO::PARAM_STR);
        $statement->bindParam(3, $post['title'], \PDO::PARAM_STR);
        $statement->bindParam(4, $post['total'], \PDO::PARAM_INT);
        $statement->bindParam(5, $post['period'], \PDO::PARAM_INT);
        $statement->bindValue(6, $due, \PDO::PARAM_STR);
        $statement->bindParam(7, $post['status'], \PDO::PARAM_INT);
        $statement->execute();
    }
    public function addGetCompanyName($get)
    {
        $companies = $this->db->prepare('SELECT company_name, prefix ,id
            FROM companies WHERE id=?');
        $companies->bindParam(1, $get['id'], \PDO::PARAM_INT);
        $companies->execute();
        $company = $companies->fetch();
        return $company;
    }
}
