<?php
// Danh muc doi tuong
Route::get('', ['as'=>'admin.examinations', 'uses' =>'ExaminationsController@index']);
Route::post('/loadList', 'ExaminationsController@loadList');
Route::post('/add', 'ExaminationsController@add');
Route::post('/update', 'ExaminationsController@update');
Route::post('/delete', 'ExaminationsController@delete');
Route::post('/edit', 'ExaminationsController@edit');
Route::post('/exportCache', 'ExaminationsController@exportCache');


// Danh muc doi tuong
Route::get('questions/{id}', ['as'=>'admin.questions', 'uses' =>'QuestionsController@index']);
Route::post('questions/loadList', 'QuestionsController@loadList');
Route::post('questions/add', 'QuestionsController@add');
Route::post('questions/update', 'QuestionsController@update');
Route::post('questions/delete', 'QuestionsController@delete');
Route::post('questions/edit', 'QuestionsController@edit');
Route::post('questions/show', 'QuestionsController@show');
Route::post('questions/exportCache', 'QuestionsController@exportCache');