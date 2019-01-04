<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware('auth')->group(function () {
    Route::prefix('file')->group(function () {
        Route::prefix('admin')->group(function () {
            Route::get('test', 'Admin\FileController@test');
            Route::get('index', 'Admin\FileController@getFiles');
            Route::post('create', 'Admin\FileController@createFolder');
            Route::post('check', 'Admin\FileController@checkFile');
            Route::get('policy', 'Admin\FileController@getPolicy');
            Route::delete('batch', 'Admin\FileController@deleteFiles');
        });

        Route::prefix('web')->group(function () {
            Route::get('index', 'Web\FileController@getFiles');
            Route::post('create', 'Web\FileController@createFolder');
            Route::post('check', 'Web\FileController@checkFile');
            Route::get('policy', 'Web\FileController@getPolicy');
            Route::delete('batch', 'Web\FileController@deleteFiles');
        });
    });
});
