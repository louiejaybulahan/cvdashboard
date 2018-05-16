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

// OBTR MENUS v1.0

Route::group(['prefix' => 'obtrfileupload', 'as' => 'obtrfileupload.'], function () {   
    Route::get('/','ObtrfileuploadController@index')->name('index');    
    Route::post('/','ObtrfileuploadController@index')->name('index');    
    Route::post('uploadfile','ObtrfileuploadController@uploadfile')->name('uploadfile');
    Route::get('renderresult','ObtrfileuploadController@renderresult')->name('renderresult');         
});


