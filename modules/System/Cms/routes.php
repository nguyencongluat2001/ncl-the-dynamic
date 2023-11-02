<?php

// CheckSupperSystemLogin Permision
Route::group(['middleware' => ['CheckAdminOwnerLogin']], function () {

});
//Quan tri chuyen muc
Route::get('categories/', 'CategoriesController@index');
Route::get('categories/loadList', 'CategoriesController@loadList');
Route::get('categories/create', 'CategoriesController@create');
Route::get('categories/edit', 'CategoriesController@edit');
Route::post('categories/update', 'CategoriesController@update');
Route::post('categories/delete', 'CategoriesController@delete');
Route::get('categories/addList', 'CategoriesController@addList');
Route::post('categories/saveList', 'CategoriesController@saveList');
// Zend tree
Route::get('zendtree/getcategories', 'TreeController@getCategories');
Route::get('zendtree/getchildcategories', 'TreeController@getchildcategories');
//Quản trị ảnh background
Route::get('images-background', 'ImagesBackgroundController@index');
Route::post('images-background/loadlist', 'ImagesBackgroundController@loadlist');
Route::post('images-background/images_add', 'ImagesBackgroundController@images_add');
Route::post('images-background/images_edit', 'ImagesBackgroundController@images_edit');
Route::post('images-background/delete', 'ImagesBackgroundController@images_delete');
Route::post('images-background/update', 'ImagesBackgroundController@images_update');
//Quản trị ảnh trang chủ
Route::get('images', 'ImagesController@index');
Route::post('images/loadlist', 'ImagesController@loadlist');
Route::post('images/images_add', 'ImagesController@images_add');
Route::post('images/images_edit', 'ImagesController@images_edit');
Route::post('images/delete', 'ImagesController@images_delete');
Route::post('images/update', 'ImagesController@images_update');
//Quan tri van ban
Route::get('documents', 'DocumentController@index');
Route::post('documents/loadlist', 'DocumentController@loadlist');
Route::post('documents/documents_add', 'DocumentController@documents_add');
Route::post('documents/documents_edit', 'DocumentController@documents_edit');
Route::post('documents/update', 'DocumentController@update');
Route::post('documents/delete', 'DocumentController@documents_delete');
Route::get('documents/renamefile', 'DocumentController@renamefile');
Route::post('documents/deletefile', 'DocumentController@deletefile');

//Quan tri bai viet
Route::get('articles', 'ArticlesController@index');
Route::post('articles/loadlist', 'ArticlesController@loadlist');
Route::post('articles/create', 'ArticlesController@create');
Route::post('articles/edit', 'ArticlesController@edit');
Route::post('articles/delete', 'ArticlesController@delete');
Route::post('articles/update', 'ArticlesController@update');

Route::post('articles/articles_add', 'ArticlesController@articles_add');
Route::post('articles/articles_edit', 'ArticlesController@articles_edit');
Route::post('articles/manager_comment', 'ArticlesController@manager_comment');
Route::post('articles/loadlist_comment', 'ArticlesController@loadlist_comment');
Route::post('articles/see_comment', 'ArticlesController@see_comment');
Route::post('articles/approve_comment', 'ArticlesController@approve_comment');
Route::post('articles/delete_comment', 'ArticlesController@delete_comment');
Route::post('articles/deletefile', 'ArticlesController@deletefile');
Route::post('articles/approval', 'ArticlesController@approval');
Route::post('articles/update_approval', 'ArticlesController@update_approval');
Route::post('articles/refuse', 'ArticlesController@refuse');
Route::post('articles/see', 'ArticlesController@see');
Route::post('articles/check_duyet', 'ArticlesController@check_duyet');
Route::get('articles/view_detail', 'ArticlesController@view_detail');
//Quan tri anh trang chu
//Quan tri album anh
Route::get('album-images', 'AlbumImagesController@index');
Route::post('album-images/loadlist', 'AlbumImagesController@loadlist');
Route::post('album-images/album-images_add', 'AlbumImagesController@albumimages_add');
Route::post('album-images/album-images_edit', 'AlbumImagesController@albumimages_edit');
Route::post('album-images/delete', 'AlbumImagesController@albumimages_delete');
Route::post('album-images/update', 'AlbumImagesController@albumimages_update');
Route::post('album-images/album-images_manager', 'AlbumImagesController@albumimages_manager');
Route::post('album-images/loadlist_image', 'AlbumImagesController@loadlist_image');
Route::post('album-images/image-add', 'AlbumImagesController@image_add');
Route::post('album-images/image_edit', 'AlbumImagesController@image_edit');
Route::post('album-images/image_delete', 'AlbumImagesController@image_delete');
Route::post('album-images/update_image', 'AlbumImagesController@update_image');
//Quan tri videos
Route::get('videos', 'VideosController@index');
Route::post('videos/loadlist', 'VideosController@loadlist');
Route::post('videos/videos_add', 'VideosController@videos_add');
Route::post('videos/videos_edit', 'VideosController@videos_edit');
Route::post('videos/delete', 'VideosController@videos_delete');
Route::post('videos/update', 'VideosController@videos_update');
Route::get('videos/convertarticles', 'VideosController@convertarticles');

//Quan tri hoi dap
Route::get('questions', 'QuestionsController@index');
Route::post('questions/loadlist', 'QuestionsController@loadlist');
Route::post('questions/questions_add', 'QuestionsController@questions_add');
Route::post('questions/update', 'QuestionsController@questions_update');
Route::post('questions/questions_edit', 'QuestionsController@questions_edit');
Route::post('questions/delete', 'QuestionsController@questions_delete');
Route::post('questions/answer_question', 'QuestionsController@answer_question');
Route::post('questions/send-answer', 'QuestionsController@reply');
// quan tri gop y
Route::get('citizen-idea', 'CitizenIdeaController@index');
Route::post('citizen-idea/loadlist', 'CitizenIdeaController@loadlist');
Route::post('citizen-idea/edit', 'CitizenIdeaController@edit');
Route::post('citizen-idea/update', 'CitizenIdeaController@update');
Route::post('citizen-idea/delete', 'CitizenIdeaController@delete');

//quản trị bảo cáo
Route::get('reports', 'ReportsController@index');
Route::post('reports/loadlist', 'ReportsController@loadlist');
Route::post('reports/export_excel', 'ReportsController@export_excel');
Route::get('reports/detail', 'ReportsController@detail');
//Quan tri Lịch công tác
Route::get('scheduler', 'SchedulerController@index');
Route::post('scheduler/loadlist', 'SchedulerController@loadlist');
Route::post('scheduler/scheduler_add', 'SchedulerController@scheduler_add');
Route::post('scheduler/scheduler_edit', 'SchedulerController@scheduler_edit');
Route::post('scheduler/delete', 'SchedulerController@scheduler_delete');
Route::post('scheduler/update', 'SchedulerController@scheduler_update');
Route::get('scheduler/convertarticles', 'SchedulerController@convertarticles');
//Quan tri bai viet gioi thieu sach
Route::get('articlesbook', 'ArticlesBookController@index');
Route::post('articlesbook/loadlist', 'ArticlesBookController@loadlist');
Route::post('articlesbook/articlesbook_add', 'ArticlesBookController@articlesbook_add');
Route::post('articlesbook/articlesbook_edit', 'ArticlesBookController@articlesbook_edit');
Route::post('articlesbook/delete', 'ArticlesBookController@articlesbook_delete');
Route::post('articlesbook/update', 'ArticlesBookController@articlesbook_update');
Route::post('articlesbook/search_publications', 'ArticlesBookController@search_documents');
Route::post('articlesbook/load_publication', 'ArticlesBookController@load_document');
Route::post('articlesbook/manager_comment', 'ArticlesBookController@manager_comment');
Route::post('articlesbook/loadlist_comment', 'ArticlesBookController@loadlist_comment');
Route::post('articlesbook/see_comment', 'ArticlesBookController@see_comment');
Route::post('articlesbook/approve_comment', 'ArticlesBookController@approve_comment');
Route::post('articlesbook/delete_comment', 'ArticlesBookController@delete_comment');

//Phan xu ly duyet file tren server (public\ckeditor_file)
Route::get('dirfile', 'FileController@index');
Route::post('dirfile/getAllFolderInPath', 'FileController@getAllFolderInPath');

// link liên kết
Route::get('link','LinkController@index');
Route::post('link/loadList', 'LinkController@loadList');
Route::post('link/add', 'LinkController@add');
Route::post('link/update', 'LinkController@update');
Route::post('link/delete', 'LinkController@delete');
Route::post('link/edit', 'LinkController@edit');
Route::post('list/exportCache', 'listController@exportCache');
