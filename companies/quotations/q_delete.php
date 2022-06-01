<?php
require('../../dbconnect.php');
require('../../app/controllers/QuotationsController.php');
use App\Controllers\QuotationController;

$cmp = new QuotationController;
$res = $cmp->delete($_GET);
