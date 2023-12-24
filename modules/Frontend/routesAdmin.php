<?php
use Illuminate\Support\Facades\Route;
use Modules\Frontend\Controllers\AuthController;
use Modules\Frontend\Controllers\Dashboard\HomeController;
use Modules\Frontend\Controllers\Dashboard\LoginController;
use Modules\Frontend\Controllers\Dashboard\UserController;

/** Login */
Route::controller(LoginController::class)->group(function ($router) {
    $router->get('system/login', 'index');
    $router->get('system/logout', 'logout');
    $router->post('system/loadList', 'loadList');
    $router->post('system/checkLogin', 'checklogin')->name('checkLogin');
});

// Home
Route::controller(HomeController::class)->group(function ($router) {
    $router->get('system/home/index', 'index');
    $router->get('system/user/loadList', 'loadList');
});

Route::controller(UserController::class)->group(function ($router) {
    $router->get('system/user/index', 'index');
    $router->get('system/user/loadList', 'loadList');
    $router->post('system/user/edit', 'edit');
    $router->post('system/user/createForm', 'createForm');
    $router->post('system/user/create', 'create');
    $router->post('system/user/delete', 'delete');
    $router->post('system/user/changeStatus', 'changeStatus');
    $router->post('system/user/changePass', 'changePass')->name('changePass');
    $router->post('system/user/updatePass', 'updatePass')->name('updatePass');

});