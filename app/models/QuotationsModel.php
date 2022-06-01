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
    public function fetchMaxpageSearched($get)
    {
        $counts = $this->db->prepare('SELECT COUNT(*) AS cnt FROM quotations WHERE company_id=? AND deleted IS NULL AND status=?');
        $counts -> bindParam(1, $get['id'], \PDO::PARAM_INT);
        $counts -> bindParam(2, $get['search'], \PDO::PARAM_INT);
        $counts -> execute();
        $cnt = $counts->fetch();
        return $cnt;
    }
    public function fetchMaxpage($id)
    {
        $counts = $this->db->prepare('SELECT COUNT(*) AS cnt FROM quotations WHERE company_id=? AND deleted IS NULL');
        $counts -> bindParam(1, $id, \PDO::PARAM_INT);
        $counts -> execute();
        $cnt = $counts->fetch();
        return $cnt;
    }
    public function fetchDataSearched($get)
    {
        $quotations = $this->db -> prepare('SELECT  q.id, q.no, q.title, c.manager_name ,q.total, q.validity_period, q.due_date, q.status, c.company_name
            FROM companies c , quotations q WHERE c.id=? AND q.company_id = c.id AND q.deleted IS NULL AND q.status=? ORDER BY q.no ASC ');//LIMIT ?,10
        $quotations -> bindParam(1, $get['id'], \PDO::PARAM_INT);
        $quotations -> bindParam(2, $get['search'], \PDO::PARAM_INT);
        $quotations -> execute();
        return $quotations;
    }
    public function fetchDataById($id)
    {
        $quotations = $this->db -> prepare('SELECT  q.id, q.no, q.title, c.manager_name ,q.total, q.validity_period, q.due_date, q.status, c.company_name
            FROM companies c , quotations q WHERE c.id=? AND q.company_id = c.id AND q.deleted IS NULL ORDER BY q.no ASC ');//LIMIT ?,10
        $quotations -> bindParam(1, $id, \PDO::PARAM_INT);
        $quotations -> execute();
        return $quotations;
    }
    //prefix追加 //index/add/edit
    public function fetchCompanyNameById($id)
    {
        $companies = $this->db -> prepare('SELECT  company_name, id, prefix FROM companies WHERE id=? ');
        $companies -> bindParam(1, $id, \PDO::PARAM_INT);
        $companies -> execute();
        $company = $companies -> fetch();
        return $company;
    }
    //add
    public function fetchId($id)
    {
        $getids = $this->db->prepare('SELECT count(*)+1 AS getid FROM quotations WHERE company_id=?');//idを取得
        $getids->bindParam(1, $id, \PDO::PARAM_INT);
        $getids->execute();
        $getid = $getids->fetch();
        return $getid;
    }
    public function create($id, $post, $due, $no)
    {
        $statement = $this->db->prepare('INSERT INTO quotations 
            SET company_id=?,no=?,
            title=?, total=?, validity_period=?, due_date=?, status=?, 
            created=NOW(),modified=NOW()');
        $statement->bindParam(1, $id, \PDO::PARAM_INT);
        $statement->bindParam(2, $no, \PDO::PARAM_STR);
        $statement->bindParam(3, $post['title'], \PDO::PARAM_STR);
        $statement->bindParam(4, $post['total'], \PDO::PARAM_INT);
        $statement->bindParam(5, $post['period'], \PDO::PARAM_INT);
        $statement->bindValue(6, $due, \PDO::PARAM_STR);
        $statement->bindParam(7, $post['status'], \PDO::PARAM_INT);
        $statement->execute();
    }
    // public function addGetCompanyName($get)
    // {
    //     $companies = $this->db->prepare('SELECT company_name, prefix ,id
    //         FROM companies WHERE id=?');
    //     $companies->bindParam(1, $get['id'], \PDO::PARAM_INT);
    //     $companies->execute();
    //     $company = $companies->fetch();
    //     return $company;
    // }
    //edit
    // public function editGetCompanyName($get)
    // {
    //     $companies = $this->db -> prepare('SELECT id, company_name, prefix
    //         FROM companies WHERE id=?');
    //     $companies->bindParam(1, $get['cid'], \PDO::PARAM_INT);
    //     $companies -> execute();
    //     $company = $companies -> fetch();
    //     return $company;
    // }
    public function fetchDataByQuotationId($id)
    {
        $quotations = $this->db -> prepare('SELECT no, title, total, validity_period, due_date, status 
            FROM quotations WHERE id = ?');
        $quotations ->bindParam(1, $id, \PDO::PARAM_INT);
        $quotations -> execute();
        $quotation = $quotations -> fetch();
        return $quotation;
    }
    public function edit($id, $post)
    {
        $statement = $this->db->prepare('UPDATE quotations
        SET  title=?, total=?, validity_period=?, due_date=?, status=?,
        modified=NOW() WHERE id=?');
        $statement->bindParam(1, $post['title'], \PDO::PARAM_STR);
        $statement->bindParam(2, $post['total'], \PDO::PARAM_INT);
        $statement->bindParam(3, $post['period'], \PDO::PARAM_INT);
        $statement->bindParam(4, $post['due'], \PDO::PARAM_INT);
        $statement->bindParam(5, $post['status'], \PDO::PARAM_INT);
        $statement->bindParam(6, $id, \PDO::PARAM_INT);
        $statement->execute();
    }
    //delete
    public function delete($id)
    {
        $del = $this->db->prepare('UPDATE quotations SET deleted=NOW() WHERE id=?');
        $del -> bindParam(1, $id, \PDO::PARAM_INT);
        $del -> execute();
    }
}
