<?php
require_once('/var/www/html/training/programming_training/config.php');
require_once(HOME.'/xcelFolder/MakePdf.php');
require(HOME.'/vendor/autoload.php');//念のため？
use XcelFolder\MakePdf;

$value = [
    "name" => "株式会社取引先",
    "pay" => "2020/02/02",
    "total" => "100000",
    "manager" => "テスト太郎",
    "num" => "yep30-i-00000011",
    "date" => "2020/07/07",
];

$xcl = new MakePdf;
// $filename = $xcl->makeexl($value);

$filename = $xcl->makepdf($value);
