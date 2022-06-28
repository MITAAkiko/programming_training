<?php
require_once('/var/www/html/training/programming_training/config.php');
require_once(APP.'/controllers/MakePdfController.php');
require(HOME.'/vendor/autoload.php');//念のため？
use App\Controllers\MakePdfController;

$xcl = new MakePdfController;
$xcl->makeInvoice($_GET);
