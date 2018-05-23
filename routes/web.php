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


Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
    Route::get('/','UsersController@index')->name('index');    
    Route::get('register','UsersController@register')->name('register');    
    Route::post('save','UsersController@save')->name('save');    
    //Route::post('list','UsersController@list')->name('list');    
    Route::post('edit','UsersController@edit')->name('edit');        
    //Route::post('permission','UsersController@permission')->name('permission');
    //Route::post('municipal','UsersController@municipal')->name('municipal');
    Route::post('remove','UsersController@remove')->name('remove');    
    //Route::post('savepermission','UsersController@savepermission')->name('savepermission');
});

Route::group(['prefix' => 'listeducation', 'as' => 'listeducation.'], function () {
    Route::get('/','ListEducationController@index')->name('index');            
    Route::post('filter','ListEducationController@filter')->name('filter');    
    Route::post('search','ListEducationController@search')->name('search');    
    Route::get('rebuildfilter','ListEducationController@rebuildfilter')->name('rebuildfilter');
    Route::get('summary','ListEducationController@showSummary')->name('summary');  
    Route::post('getprovince','ListEducationController@getProvince')->name('getprovince');
    Route::post('getmunicipality','ListEducationController@getMunicipality')->name('getmunicipality');
    Route::post('getbrgy','ListEducationController@getBrgy')->name('getbrgy');
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
});


Route::group(['prefix' => 'backgroundprocess', 'as' => 'backgroundprocess.'], function () {       
    Route::get('/','BackgroundprocessController@index')->name('index');          
    Route::get('checkscript','BackgroundprocessController@checkscript')->name('checkscript');      
    Route::post('addscript','BackgroundprocessController@addscript')->name('addscript');  
    Route::post('remove','BackgroundprocessController@remove')->name('remove');      
    Route::post('status','BackgroundprocessController@status')->name('status');      
});


Route::get('profile','SiteController@index')->name('profile');

Route::get('error',function(){ return view('error'); });


require_once('web_route1.php');
require_once('web_route2.php');

