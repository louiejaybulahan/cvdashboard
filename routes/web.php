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


Route::get('/','SiteController@index');

Auth::routes();

Route::get('/home',function(){ return Redirect::to('site'); });

/*
Route::get('/', function () {   
    return view('welcome');
});
Route::get('/submit', function () {
    return view('submit');
});
*/
Route::get('/logout',function(){ return Redirect::to('login'); });
Route::get('/loguser','Auth\LoginController@loguser')->name('loguser');     

Route::group(['prefix' => 'site', 'as' => 'site.'], function () {
    Route::get('/','SiteController@index')->name('index');      
    Route::get('paidunpaidbrgy','SiteController@getPaidUnpaidByBarangay')->name('paidunpaidbrgy');      
    Route::get('grandtotalpaidunpaidbyperiod','SiteController@getGrandTotalPaidUnpaidByPeriod')->name('grandtotalpaidunpaidbyperiod');      
    Route::post('saveRequest','SiteController@saveRequest')->name('saveRequest');      
    Route::get('removeRequest','SiteController@removeRequest')->name('removeRequest');
    Route::get('paidunpaid','SiteController@paidunpaid')->name('paidunpaid');    
    Route::get('totalbeneficiarybyprovince','SiteController@totalbeneficiarybyprovince')->name('totalbeneficiarybyprovince');    
});

Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
    Route::get('/','UsersController@index')->name('index');    
    Route::get('register','UserController@register')->name('register');    
    Route::post('save','UserController@save')->name('save');    
    //Route::post('list','UserController@list')->name('list');    
    Route::post('edit','UserController@edit')->name('edit');        
    Route::post('permission','UserController@permission')->name('permission');
    Route::post('municipal','UserController@municipal')->name('municipal');
    Route::post('remove','UserController@remove')->name('remove');    
    Route::post('savepermission','UserController@savepermission')->name('savepermission');
});

Route::group(['prefix' => 'listeducation', 'as' => 'listeducation.'], function () {
    Route::get('/','ListEducationController@index')->name('index');            
    Route::post('filter','ListEducationController@filter')->name('filter');    
    Route::post('search','ListEducationController@search')->name('search');    
    Route::get('rebuildfilter','ListEducationController@rebuildfilter')->name('rebuildfilter');
    Route::get('summary','ListEducationController@showSummary')->name('summary');              
});

Route::group(['prefix' => 'listfds', 'as' => 'listfds.'], function () {
    Route::get('/','ListFdsController@index')->name('index');            
    Route::post('filter','ListFdsController@filter')->name('filter');    
    Route::post('search','ListFdsController@search')->name('search');    
    Route::get('rebuildfilter','ListFdsController@rebuildfilter')->name('rebuildfilter');
    Route::get('summary','ListFdsController@showSummary')->name('summary');              
});

Route::group(['prefix' => 'listhealth', 'as' => 'listhealth.'], function () {
    Route::get('/','ListHealthController@index')->name('index');            
    Route::post('filter','ListHealthController@filter')->name('filter');    
    Route::post('search','ListHealthController@search')->name('search');    
    Route::get('rebuildfilter','ListHealthController@rebuildfilter')->name('rebuildfilter');
    Route::get('summary','ListHealthController@showSummary')->name('summary');              
});

Route::group(['prefix' => 'listturnout', 'as' => 'listturnout.'], function () {
    Route::get('/','ListTurnoutController@index')->name('index');            
    Route::post('filter','ListTurnoutController@filter')->name('filter');    
    Route::post('search','ListTurnoutController@search')->name('search');    
    Route::get('rebuildfilter','ListTurnoutController@rebuildfilter')->name('rebuildfilter');
    Route::get('summary','ListTurnoutController@showSummary')->name('summary');              
});

Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
    Route::get('/','SettingsController@index')->name('index');          
    Route::post('save','SettingsController@save')->name('save');
    Route::post('rebuildfilter','SettingsController@rebuildfilter')->name('rebuildfilter');
    Route::get('newstorage','SettingsController@newstorage')->name('newstorage');          
});

Route::group(['prefix' => 'uploadfile', 'as' => 'uploadfile.'], function () {   
    Route::get('/','UploadfileController@index')->name('index');    
    Route::post('/','UploadfileController@index')->name('index');    
    Route::post('loadfile','UploadfileController@loadfile')->name('loadfile');
    Route::get('renderfile','UploadfileController@renderfile')->name('renderfile');         
});


Route::group(['prefix' => 'uploadfilenoncom', 'as' => 'uploadfilenoncom.'], function () {   
    Route::get('/','UploadfilenoncomController@index')->name('index');    
    Route::post('/','UploadfilenoncomController@index')->name('index');    
    Route::post('loadfile','UploadfilenoncomController@loadfile')->name('loadfile');
    Route::get('renderfile','UploadfilenoncomController@renderfile')->name('renderfile');
    //Route::post('save','SettingsController@save')->name('save');
    //Route::post('rebuildfilter','SettingsController@rebuildfilter')->name('rebuildfilter');
    //Route::get('newstorage','SettingsController@newstorage')->name('newstorage');          
});

Route::get('profile','SiteController@index')->name('profile');

Route::get('error',function(){ return view('error'); });
