<?php
// quản trị doi tuong
Route::get('', ['as'=>'admin.examinations', 'uses' =>'ObjectsController@index']);
Route::post('/loadList', 'ObjectsController@loadList');
Route::post('/add', 'ObjectsController@add');
Route::post('/update', 'ObjectsController@update');
Route::post('/delete', 'ObjectsController@delete');
Route::post('/edit', 'ObjectsController@edit');
Route::post('/exportCache', 'ObjectsController@exportCache');
Route::post('/get-unit', 'ObjectsController@getUnit');
Route::post('/get-dot-thi', 'ObjectsController@getDotThi');
Route::post('/show', 'ObjectsController@show');


