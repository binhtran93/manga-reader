<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// manga
Route::get('/mangas', 'MangaController@getMangaList');
Route::get('/test', 'MangaController@test');

// tag
Route::get('/tags', 'TagController@getTags');

// author
Route::get('/authors', 'AuthorController@getAuthors');