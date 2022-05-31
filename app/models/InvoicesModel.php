<?php
namespace App\Models;

class InvoicesModel
{
    private $db;
    public function __construct()
    {
        $this->db = new \PDO('mysql:dbname=programming_training;host=127.0.0.1;charset=utf8', 'root', 'P@ssw0rd');
    }
    //index
    public function getMaxpageSearched($get)
    {
        $counts = $this->db->prepare('SELECT COUNT(*) AS cnt FROM invoices WHERE company_id=? AND deleted IS NULL AND status=?');
        $counts -> bindParam(1, $get['id'], \PDO::PARAM_INT);
        $counts -> bindParam(2, $get['search'], \PDO::PARAM_INT);
        $counts -> execute();
        $cnt = $counts->fetch();
        return $cnt;
    }
    public function getMaxpage($get)
    {
        $counts = $this->db->prepare('SELECT COUNT(*) AS cnt FROM invoices WHERE company_id=? AND deleted IS NULL');
        $counts -> bindParam(1, $get['id'], \PDO::PARAM_INT);
        $counts -> execute();
        $cnt = $counts->fetch();
        return $cnt;
    }
    public function showDataSearchedASC($get, $start)
    {
        $invoices = $this->db -> prepare('SELECT   i.id, i.no, i.title, c.manager_name ,i.total, i.payment_deadline, i.date_of_issue, i.quotation_no, i.status, c.company_name
            FROM companies c , invoices i WHERE c.id=? AND i.company_id = c.id AND i.deleted IS NULL AND i.status=? ORDER BY i.no ASC LIMIT ?,10');
        $invoices -> bindParam(1, $get['id'], \PDO::PARAM_INT);
        $invoices -> bindParam(2, $get['search'], \PDO::PARAM_INT);
        $invoices -> bindParam(3, $start, \PDO::PARAM_INT);
        $invoices -> execute();
        return $invoices;
    }
    public function showDataSearchedDESC($get, $start)
    {
        $invoices = $this->db -> prepare('SELECT   i.id, i.no, i.title, c.manager_name ,i.total, i.payment_deadline, i.date_of_issue, i.quotation_no, i.status, c.company_name
            FROM companies c , invoices i WHERE c.id=? AND i.company_id = c.id AND i.deleted IS NULL AND i.status=? ORDER BY i.no DESC LIMIT ?,10');
        $invoices -> bindParam(1, $get['id'], \PDO::PARAM_INT);
        $invoices -> bindParam(2, $get['search'], \PDO::PARAM_INT);
        $invoices -> bindParam(3, $start, \PDO::PARAM_INT);
        $invoices -> execute();
        return $invoices;
    }
    public function showDataASC($get, $start)
    {
        $invoices = $this->db -> prepare('SELECT  i.id, i.no, i.title, c.manager_name ,i.total, i.payment_deadline, i.date_of_issue, i.quotation_no, i.status, c.company_name
            FROM companies c , invoices i WHERE c.id=? AND i.company_id = c.id AND i.deleted IS NULL ORDER BY i.no ASC LIMIT ?,10');
        $invoices -> bindParam(1, $get['id'], \PDO::PARAM_INT);
        $invoices -> bindParam(2, $start, \PDO::PARAM_INT);
        $invoices -> execute();
        return $invoices;
    }
    public function showDataDESC($get, $start)
    {
        $invoices = $this->db -> prepare('SELECT  i.id, i.no, i.title, c.manager_name ,i.total, i.payment_deadline, i.date_of_issue, i.quotation_no, i.status, c.company_name
            FROM companies c , invoices i WHERE c.id=? AND i.company_id = c.id AND i.deleted IS NULL ORDER BY i.no DESC LIMIT ?,10');
        $invoices -> bindParam(1, $get['id'], \PDO::PARAM_INT);
        $invoices -> bindParam(2, $start, \PDO::PARAM_INT);
        $invoices -> execute();
        return $invoices;
    }
    public function showCompanyName($get)
    {
        $companies = $this->db -> prepare('SELECT  company_name, id FROM companies WHERE id=? AND deleted IS NULL');
        $companies -> bindParam(1, $get['id'], \PDO::PARAM_INT);
        $companies -> execute();
        $company = $companies -> fetch();
        return $company;
    }
    //add
    public function addGetId($get)
    {
        $getids = $this->db->prepare('SELECT count(*)+1 AS getid FROM invoices WHERE company_id=?');//idを取得
        $getids->bindParam(1, $get['id'], \PDO::PARAM_INT);
        $getids->execute();
        $getid = $getids->fetch();
        return $getid;
    }
    public function addData($get, $post, $no)
    {
        $statement = $this->db->prepare('INSERT INTO invoices SET company_id=?,no=?,
            title=?, total=?, payment_deadline=?, date_of_issue=?, quotation_no=?, status=?, 
            created=NOW(),modified=NOW()');
        $statement->bindParam(1, $get['id'], \PDO::PARAM_INT);
        $statement->bindParam(2, $no, \PDO::PARAM_STR);
        $statement->bindParam(3, $post['title'], \PDO::PARAM_STR);
        $statement->bindParam(4, $post['total'], \PDO::PARAM_INT);
        $statement->bindParam(5, $post['pay'], \PDO::PARAM_INT);
        $statement->bindParam(6, $post['date'], \PDO::PARAM_INT);
        $statement->bindParam(7, $post['quo'], \PDO::PARAM_STR);
        $statement->bindParam(8, $post['status'], \PDO::PARAM_INT);
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
    //edit
    public function editGetCompanyName($get)
    {
        $companies = $this->db -> prepare('SELECT id, company_name, prefix
            FROM companies WHERE id=?');
        $companies -> bindParam(1, $get['cid'], \PDO::PARAM_INT);
        $companies -> execute();
        $company = $companies -> fetch();
        return $company;
    }
    public function editGetData($get)
    {
        $invoices = $this->db -> prepare('SELECT no, title, total, payment_deadline, date_of_issue, quotation_no, status 
            FROM invoices WHERE id = ?');
        $invoices -> bindParam(1, $get['id'], \PDO::PARAM_INT);
        $invoices -> execute();
        $invoice = $invoices -> fetch();
        return $invoice;
    }
    public function editData($get, $post)
    {
        $statement = $this->db->prepare('UPDATE invoices
            SET  title=?, total=?, payment_deadline=?, date_of_issue=?, status=?,
            modified=NOW() WHERE id=?');
        $statement->bindParam(1, $post['title'], \PDO::PARAM_STR);
        $statement->bindParam(2, $post['total'], \PDO::PARAM_INT);
        $statement->bindParam(3, $post['pay'], \PDO::PARAM_INT);
        $statement->bindParam(4, $post['date'], \PDO::PARAM_INT);
        $statement->bindParam(5, $post['status'], \PDO::PARAM_INT);
        $statement->bindParam(6, $get['id'], \PDO::PARAM_INT);
        $statement->execute();
    }
    public function delete($id)
    {
        $del = $this->db->prepare('UPDATE invoices SET deleted=NOW() WHERE id=?');
        $del -> bindParam(1, $id, \PDO::PARAM_INT);
        $del -> execute();
    }
}
