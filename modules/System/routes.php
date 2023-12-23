<?php
use Illuminate\Support\Facades\Route;
use MModules\System\Login\Controllers\LoginController;

Route::controller(LoginController::class)->group(function ($router) {
    $router->get('/system/login', 'index');

});