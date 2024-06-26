<?php

use Illuminate\Support\Facades\Route;
use Modules\Frontend\Controllers\AuthController;
use Modules\Frontend\Controllers\HealthCertificate;
use Modules\Frontend\Controllers\HomeController;
use Modules\Frontend\Controllers\ShopController;
use Modules\Frontend\Controllers\BlogController;
use Modules\Frontend\Controllers\ContactController;
use Modules\Frontend\Controllers\DegressController;
Route::controller(AuthController::class)->group(function ($router) {
    $router->get('login', 'getSignIn');
    $router->post('checkLogin', 'signIn');
    $router->get('register', 'getSignUp');
    $router->post('checkRegister', 'signUp');
    $router->get('quen-mat-khau', 'getForgotPassword');
    $router->post('quen-mat-khau', 'forgotPassword');
    $router->get('logout', 'logOut');
    $router->get('getUnitApi', 'getUnitApi');
    $router->get('getHuyen', 'getHuyen');
    $router->get('getXa', 'getXa');
    $router->get('loadata', 'index');
});

/** Trang chủ */
Route::controller(HomeController::class)->group(function ($router) {
    $router->get('', 'index');
    $router->get('/trang-chu', 'index');
    $router->get('/bang', 'getData');

});
/** Trang chủ */
Route::controller(ShopController::class)->group(function ($router) {
    $router->get('shop', 'index');
});
/** Trang chủ */
Route::controller(BlogController::class)->group(function ($router) {
    $router->get('blog-detail/{code_blog}', 'index');
    $router->post('blog-detail/{code_blog}/createGiayKham', 'createGiayKham');
    $router->post('blog-detail/{code_blog}/createBangCap', 'createBangCap');
});
/** Trang chủ */
Route::controller(ContactController::class)->group(function ($router) {
    $router->get('bang', 'index');
});

Route::controller(HealthCertificate::class)->group(function ($router) {
    $router->get('giaykham', 'index');
});

Route::controller(DegressController::class)->group(function ($router) {
    $router->get('bang', 'index');
});
