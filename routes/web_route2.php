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


Route::group(['prefix' => 'site', 'as' => 'site.'], function () {
    Route::get('/','SiteController@index')->name('index');      
	Route::get('exportexcel','SiteController@exportexcel')->name('exportexcel');      
	Route::post('exportexcel','SiteController@exportexcel')->name('exportexcel');      
	Route::post('dashboarddata','SiteController@dashboarddata')->name('dashboarddata');      
	Route::get('dashboarddata','SiteController@dashboarddata')->name('dashboarddata');      
    Route::get('paidunpaidbrgy','SiteController@getPaidUnpaidByBarangay')->name('paidunpaidbrgy');      
    Route::get('grandtotalpaidunpaidbyperiod','SiteController@getGrandTotalPaidUnpaidByPeriod')->name('grandtotalpaidunpaidbyperiod');      
    Route::post('saveRequest','SiteController@saveRequest')->name('saveRequest');      
    Route::get('removeRequest','SiteController@removeRequest')->name('removeRequest');
    Route::get('paidunpaid','SiteController@paidunpaid')->name('paidunpaid');    
    Route::get('totalbeneficiarybyprovince','SiteController@totalbeneficiarybyprovince')->name('totalbeneficiarybyprovince');    
});

Route::group(['prefix' => 'obtrfileupload', 'as' => 'obtrfileupload.'], function () {   
    Route::get('/','ObtrfileuploadController@index')->name('index');    
    Route::post('/','ObtrfileuploadController@index')->name('index');    
    Route::post('uploadfile','ObtrfileuploadController@uploadfile')->name('uploadfile');
    Route::get('renderresult','ObtrfileuploadController@renderresult')->name('renderresult');         
});


