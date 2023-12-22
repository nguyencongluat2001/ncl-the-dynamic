<?php
 //Danh mục
 Route::post('/createForm','CateController@createForm');
 Route::post('/create','CateController@create');
 Route::post('/edit','CateController@edit');
 Route::post('/delete','CateController@delete');
 Route::get('/index', 'CateController@index');
 Route::get('/loadList','CateController@loadList');
 Route::post('/updateCategory','CateController@updateCategory');
 Route::post('/changeStatusCate','CateController@changeStatusCate');
 //thể loại
 Route::get('/indexCategory', 'CategoryController@indexCategory');
 Route::get('/loadListCategory','CategoryController@loadListCategory');
 Route::post('/createFormCategory','CategoryController@createFormCategory');
 Route::post('/createCategory','CategoryController@createCategory');
 Route::post('/editCategory','CategoryController@edit');
 Route::post('/deleteCategory','CategoryController@,delete');
 Route::post('/updateCategoryCate','CategoryController@updateCategoryCate');
 Route::post('/changeStatusCategoryCate','CategoryController@changeStatusCategoryCate');