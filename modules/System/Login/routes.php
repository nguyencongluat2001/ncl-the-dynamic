<?php
Route::get('', 'LoginController@index');
Route::get('test', 'LoginController@test');
Route::post('loadList', 'LoginController@loadList');
Route::post('checkLogin', 'LoginController@checklogin')->name('/checkLogin');
Route::get('/logout', 'LoginController@logout');  
Route::get('/login', 'LoginController@index');    
Route::get('changepassword', 'LoginController@changePassword');  
Route::post('updateChangePassword', 'LoginController@updateChangePassword');  