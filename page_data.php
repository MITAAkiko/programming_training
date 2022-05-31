<?php
require_once('dbconnect.php');
require_once('page_class.php');
require_once('index.php');
//require_once('quotations/index.php');

//会社一覧用
//maxPage
if (!empty($_GET['search'])) {
    $searched = '%'.$_GET['search'].'%' ;
    $counts = $db->prepare('SELECT COUNT(*) AS cnt FROM companies WHERE deleted IS NULL AND (company_name LIKE ? OR manager_name LIKE ?)');
    $counts->bindParam(1, $searched, PDO::PARAM_STR);
    $counts->bindParam(2, $searched, PDO::PARAM_STR);
    $counts->execute();
    $cnt = $counts->fetch();
    $maxPage = ceil($cnt['cnt']/10);
} else {
    $counts = $db->query('SELECT COUNT(*) AS cnt FROM companies WHERE deleted IS NULL');
    $cnt = $counts->fetch();
    $maxPage = ceil($cnt['cnt']/10);
}
/*
//見積一覧用
//maxPage
if (!empty($_GET['search'])) {
    $q_counts = $db->prepare('SELECT COUNT(*) AS cnt FROM quotations WHERE company_id=? AND deleted IS NULL AND status=?');
    $q_counts -> bindParam(1, $_GET['id'], PDO::PARAM_INT);
    $q_counts -> bindParam(2, $_GET['search'], PDO::PARAM_INT);
    $q_counts -> execute();
    $q_cnt = $q_counts->fetch();
    $q_maxPage = ceil($q_cnt['cnt']/10);
} else {
    $q_counts = $db->prepare('SELECT COUNT(*) AS cnt FROM quotations WHERE company_id=? AND deleted IS NULL');
    $q_counts -> bindParam(1, $_GET['id'], PDO::PARAM_INT);
    $q_counts -> execute();
    $q_cnt = $q_counts->fetch();
    $q_maxPage = ceil($q_cnt['cnt']/10);
}
*/

//page共通？
if (!empty($_GET['page'])) {
    $page = $_GET['page'];
    $page = max($page, 1);
    
    //最大値
    $page = min($page, $maxPage);
    if ($page == '') {
        $page = 1;
    }
} else {
    $page = 1;
}

$cmpPage = new Page($page, $maxPage);//会社一覧用のページ
//$quoPage = new Page($page, $q_maxPage);//見積一覧用のページ
