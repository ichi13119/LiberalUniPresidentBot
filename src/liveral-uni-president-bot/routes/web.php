<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', 'VideoController@index')->name('videos.index');
Route::resource('videos', 'VideoController');
Route::post('/videos/calculateIdf', 'VideoController@calculateIdf')->name('videos.calculateIdf');

/*
Route::get('/videos', 'VideoController@index')->name('videos.index');
Route::get('/videos/create', 'VideoController@showCreateForm')->name('videos.create');
Route::post('/videos/create', 'VideoController@create');
Route::get('/videos/{id}/edit', 'VideoController@showEditForm')->name('videos.edit');
Route::post('/videos/{id}/update', 'VideoController@update')->name('videos.update');
Route::get('/test', 'VideoController@test');
*/
