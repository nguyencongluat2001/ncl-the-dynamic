<?php
use Illuminate\Support\Facades\Route;
use Modules\Frontend\Controllers\AuthController;
use Modules\Frontend\Controllers\HomeController;
use Modules\Frontend\Controllers\ShopController;
use Modules\Frontend\Controllers\BlogController;
use Modules\Frontend\Controllers\ContactController;

Route::controller(AuthController::class)->group(function ($router) {
    $router->get('login', 'getSignIn');
    $router->post('checkLogin', 'signIn');
    $router->get('register', 'getSignUp');
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
/** Trang chủ */
Route::controller(ShopController::class)->group(function ($router) {
    $router->get('shop', 'index');
});
/** Trang chủ */
Route::controller(BlogController::class)->group(function ($router) {
    $router->get('blog', 'index');
});
/** Trang chủ */
Route::controller(ContactController::class)->group(function ($router) {
    $router->get('contact', 'index');
});
