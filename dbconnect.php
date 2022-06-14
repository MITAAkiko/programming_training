<?php
$user = 'root';
$pass = 'P@ssw0rd';
try {
    $db = new PDO('mysql:dbname=programming_training;host=127.0.0.1;charset=utf8', $user, $pass);
    $db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo '接続エラー:'.$e -> getMessage();
}
