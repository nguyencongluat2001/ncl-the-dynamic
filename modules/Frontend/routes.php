<?php
use Illuminate\Support\Facades\Route;
use Modules\Frontend\Controllers\AuthController;
use Modules\Frontend\Controllers\HomeController;

Route::controller(AuthController::class)->group(function ($router) {
    $router->get('dang-nhap', 'getSignIn');
    $router->post('dang-nhap', 'signIn');
    $router->get('dang-ky', 'getSignUp');
    $router->post('dang-ky', 'signUp');
    $router->get('quen-mat-khau', 'getForgotPassword');
    $router->post('quen-mat-khau', 'forgotPassword');
    $router->get('dang-xuat', 'logOut');
});

/** Trang chủ */
Route::controller(HomeController::class)->group(function ($router) {
    $router->get('', 'index');
    $router->get('/trang-chu', 'index');
});
