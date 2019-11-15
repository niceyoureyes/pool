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

Auth::routes();

Route::get('/',     'HomeController@index');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/in_development', function () {
    return view('in_development');
})->name('IN_DEVELOPMENT');

Route::get('/no_access', function () {
    return view('no_access');
})->name('NO_ACCESS');

/* general functions */

Route::get('/loader', function (){
    return view('loader');
})->name('loader');

Route::post('/files/load', 'FileController@load')->name('files.load');
