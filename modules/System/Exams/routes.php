<?php
// quản trị doi tuong
Route::get('', ['as'=>'admin.exams', 'uses' =>'ExamsController@index']);
Route::post('/loadList', 'ExamsController@loadList');
Route::post('/add', 'ExamsController@add');
Route::post('/update', 'ExamsController@update');
Route::post('/delete', 'ExamsController@delete');
Route::post('/edit', 'ExamsController@edit');
Route::post('/show', 'ExamsController@show');
Route::post('/exportCache', 'ExamsController@exportCache');