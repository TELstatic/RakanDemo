<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware('auth')->group(function () {
    Route::prefix('file')->group(function () {
        Route::get('test', 'Admin\FileController@test');
        Route::get('index', 'Admin\FileController@getFiles');
        Route::post('create', 'Admin\FileController@createFolder');
        Route::post('check', 'Admin\FileController@checkFile');
        Route::get('policy', 'Admin\FileController@getPolicy');
        Route::delete('batch', 'Admin\FileController@deleteFiles');
    });
});
