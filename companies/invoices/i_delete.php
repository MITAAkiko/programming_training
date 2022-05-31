<?php
require('../../dbconnect.php');
require('../../app/controllers/InvoicesController.php');
use App\Controllers\InvoicesController;

$cmp = new InvoicesController;
$cmp->delete($_GET);
// if (empty($_GET)) {
//     header('Location:./');
// } elseif ($_GET['id'] == '') {
//     header('Location:./');
// } else {
//     $id = $_GET['id'];
//     $cid = $_GET['cid'];
//     //削除する
//     $del = $db->prepare('UPDATE invoices SET deleted=NOW() WHERE id=?');
//     $del -> bindParam(1, $id, PDO::PARAM_INT);
//     $del -> execute();
//     header('Location:index.php?id='.$cid);
//     exit();
// }
