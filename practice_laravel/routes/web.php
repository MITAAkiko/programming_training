<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;

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

Route::get('/index/{id}', [CompanyController::class, 'delete'])->name('delete');


Route::get('/', function () {
    return view('welcome');
});
