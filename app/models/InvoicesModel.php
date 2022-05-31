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
}
