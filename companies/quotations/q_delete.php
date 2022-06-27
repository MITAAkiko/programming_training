<?php
require('../../app/controllers/QuotationsController.php');
use App\Controllers\QuotationController;

$cmp = new QuotationController;
$cmp->delete($_POST['delete_id'], $_POST['cid']);
