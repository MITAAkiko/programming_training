<?php
require_once('../app/controllers/CompaniesController.php');

use App\Controllers\CompaniesController;

$cmp = new CompaniesController;
$res = $cmp->delete($_GET);
