<?php
require('../../dbconnect.php');
require('../../app/controllers/InvoicesController.php');
use App\Controllers\InvoicesController;

$cmp = new InvoicesController;
$cmp->delete($_GET);
