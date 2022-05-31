<?php
require('../dbconnect.php');

//var_dump($_REQUEST);

if (empty($_GET)) {
    header('Location:./');
} elseif ($_GET['id'] == '') {
    header('Location:./');
} else {
    $id = $_GET['id'];

     //削除する
     $del = $db->prepare('UPDATE companies SET deleted=NOW() WHERE id=?');
     $del -> bindParam(1, $id, PDO::PARAM_INT);
     $del -> execute();

    header('Location:index.php');
    exit();
}
