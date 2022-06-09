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


//Route::get('/test/func', 'App\Http\Controllers\TestController@func');
Route::get('/index', [CompanyController::class,'index'])->name('index');//URI,class(上でuse),function名->ルート名
Route::get('/add', [CompanyController::class,'add'])->name('add');
Route::post('/add', [CompanyController::class, 'validation']);
//Route::match('post', '/add', [CompanyController::class, 'validation']); //get,postなど複数ある場合match
//GETリクエスト
//Route::get('/test/func?', 'App\Http\Controllers\TestController@getSearch');//
 
// Route::get('receive', 'App\Http\Controllers\TestController@receive');//受

// Route::get('/test/func', [TestController::class,'index']);
Route::get('/', function () {
    return view('welcome');
});
