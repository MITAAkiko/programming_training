<?php
require_once('/var/www/html/training/programming_training/config.php');
require_once(APP.'/controllers/MakeExcelController.php');
require(HOME.'/vendor/autoload.php');//念のため？
use App\Controllers\MakeExcelController;

$xcl = new MakeExcelController;
$xcl->excel($_GET);
