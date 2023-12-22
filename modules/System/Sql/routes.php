<?php
 //Danh mục
Route::get('/index', 'SqlController@index');
Route::get('/loadList','SqlController@loadList');