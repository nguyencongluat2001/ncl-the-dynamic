<?php
use Illuminate\Support\Facades\Route;
use Modules\Frontend\Controllers\AuthController;
use Modules\Frontend\Controllers\Dashboard\HomeController;
use Modules\Frontend\Controllers\Dashboard\LoginController;
use Modules\Frontend\Controllers\Dashboard\UserController;
use Modules\Frontend\Controllers\Dashboard\CateController;
use Modules\Frontend\Controllers\Dashboard\CategoryController;
use Modules\Frontend\Controllers\Dashboard\BlogController;
use Modules\Frontend\Controllers\Dashboard\SqlController;
use Modules\Frontend\Controllers\Dashboard\ProductController;
/** Login */
Route::controller(LoginController::class)->group(function ($router) {
    $router->get('system/login', 'index');
    $router->get('system/logout', 'logout');
    $router->post('system/loadListCategory', 'loadList');
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

    $router->get('system/userInfo/changePass', 'changePass');
    $router->post('system/userInfo/updatePass', 'updatePass');
    $router->get('system/userInfo/index', 'indexUserInfo')->name('userInfoIndex');
});

Route::controller(CateController::class)->group(function ($router) {
    $router->get('system/category/index', 'index');
    $router->get('system/category/loadList', 'loadList');
    $router->post('system/category/edit', 'edit');
    $router->post('system/category/createForm', 'createForm');
    $router->post('system/category/create', 'create');
    $router->post('system/category/delete', 'delete');
    $router->post('system/category/updateCategory', 'updateCategory');
    $router->post('system/category/changeStatusCate', 'changeStatusCate');
});

Route::controller(CategoryController::class)->group(function ($router) {
    $router->get('system/category/indexCategory', 'indexCategory');
    $router->get('system/category/loadListCategory', 'loadListCategory');
    $router->post('system/category/editCategory', 'editCategory');
    $router->post('system/category/createFormCategory', 'createFormCategory');
    $router->post('system/category/createCategory', 'createCategory');
    $router->post('system/category/deleteCategory', 'deleteCategory');
    $router->post('system/category/updateCategoryCate', 'updateCategoryCate');
    $router->post('system/category/changeStatusCategoryCate', 'changeStatusCategoryCate');
});

Route::controller(BlogController::class)->group(function ($router) {
    $router->get('system/blog/index', 'index');
    $router->get('system/blog/loadList', 'loadList');
    $router->post('system/blog/edit', 'edit');
    $router->post('system/blog/createForm', 'createForm');
    $router->post('system/blog/create', 'create');
    $router->post('system/blog/delete', 'delete');
    $router->get('system/blog/infor', 'infor');
    $router->post('system/blog/uploadFileCK', 'uploadFileCK');
    $router->post('system/blog/changeStatus', 'changeStatus');
});

//product
Route::controller(ProductController::class)->group(function ($router) {
    $router->get('system/product/index', 'index');
    $router->get('system/product/loadList', 'loadList');
});
Route::controller(SqlController::class)->group(function ($router) {
    $router->get('/system/sql/index', 'index');
    $router->get('/system/sql/loadList', 'loadList');

});
