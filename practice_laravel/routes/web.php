<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\QuotationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/index', [CompanyController::class,'index'])->name('index');

Route::get('/add', [CompanyController::class,'add'])->name('add');
Route::post('/add', [CompanyController::class, 'addValidation']);

Route::get('/edit/{id}', [CompanyController::class, 'edit'])->name('edit');
Route::post('/edit/{id}', [CompanyController::class, 'editValidation']);

//Route::post('/index', [CompanyController::class, 'delete'])->name('delete');
Route::post('/delete', [CompanyController::class, 'delete'])->name('delete');

//見積
Route::get('/quotations/index', [QuotationController::class, 'index']);

Route::get('/quotations/add', [QuotationController::class, 'add']);
// Route::post('/quotations/add', [QuotationController::class, 'addValidation']);

// Route::get('quotations/edit', [QuotationController::class, 'edit']);
// Route::post('quotations/edit', [QuotationController::class, 'editValidation']);

// Route::post('quotations/delete', [QuotationController::class, 'delete']);

//請求
Route::get('/invoices/index', [InvoiceController::class, 'index']);

Route::get('/invoices/add', [InvoiceController::class, 'add']);
Route::post('/invoices/add', [InvoiceController::class, 'addValidation']);

Route::get('invoices/edit', [InvoiceController::class, 'edit']);
Route::post('invoices/edit', [InvoiceController::class, 'editValidation']);

Route::post('invoices/delete', [InvoiceController::class, 'delete']);

Route::get('/', function () {
    return view('welcome');
});
