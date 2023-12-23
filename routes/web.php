<?php

use Illuminate\Support\Facades\Route;
use Modules\System\Login\Controllers\LoginController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::post('/system/checkLogin', [LoginController::class, 'checkLogin'])->name('checkLogin');

Route::get('system', function () {
    return redirect('system/login');
});