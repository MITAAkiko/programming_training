<?php
require('../../dbconnect.php');
require('../../app/controllers/QuotationsController.php');
use App\Controllers\QuotationController;

$cmp = new QuotationController;
$res = $cmp->delete($_GET);
// if (empty($_GET)) {
//     header('Location:./');
// } elseif ($_GET['id'] == '') {
//     header('Location:./');
// } else {
//     $id = $_GET['id'];
//     $cid = $_GET['cid'];
//     //削除する
//     $del = $db->prepare('UPDATE quotations SET deleted=NOW() WHERE id=?');
//     $del -> bindParam(1, $id, PDO::PARAM_INT);
//     $del -> execute();
//     header('Location:index.php?id='.$cid);
//     exit();
// }
