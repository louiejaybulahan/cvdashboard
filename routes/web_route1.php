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

//Aying start //
Route::group(['prefix' => 'generateturnout', 'as' => 'generateturnout.'], function () {   
    Route::get('/','GenerateturnoutController@index')->name('index');    
    Route::post('/','GenerateturnoutController@index')->name('index'); 
    Route::get('generate','GenerateturnoutController@generate')->name('generate');
  //  Route::post('loadfile','UploadfileController@loadfile')->name('loadfile');
   // Route::get('renderfile','UploadfileController@renderfile')->name('renderfile');         
});

Route::group(['prefix' => 'periodactive', 'as' => 'periodactive.'], function () {   
    Route::get('/','SetperiodactiveController@index')->name('index');    
    Route::post('/','SetperiodactiveController@index')->name('index');    
  //  Route::post('loadfile','UploadfileController@loadfile')->name('loadfile');
   // Route::get('renderfile','UploadfileController@renderfile')->name('renderfile');         
});
//--End Aying--//