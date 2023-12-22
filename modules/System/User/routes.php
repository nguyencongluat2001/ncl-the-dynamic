<?php

 Route::get('/index', 'UserController@index');
 Route::get('/loadList','UserController@loadList');
 Route::post('/edit', 'UserController@edit');
 Route::post('/createForm', 'UserController@createForm');
 Route::post('/create', 'UserController@create');
 Route::post('/delete', 'UserController@delete');
 // Cập nhật mật khẩu
 Route::post('/changeStatus', 'UserController@changeStatus');
 Route::get('/changePass', 'UserController@changePass')->name('changePass');
 Route::post('/updatePass', 'UserController@updatePass')->name('updatePass');