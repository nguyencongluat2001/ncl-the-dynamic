<?php

use Illuminate\Support\Facades\Route;

Route::controller('AuthController')->group(function () {
    Route::post('login', 'login');
    Route::post('logout', 'logout');
});

Route::resources([
    // 'record'  => RecordController::class,
]);
