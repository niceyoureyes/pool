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
//TODO middleware admin everywhere

Route::prefix('files')->group(function(){
    Route::get( 'load'   , function (){ return view('loader'); })->name('loader');
    Route::post('load'   , 'FileController@load')->name('load');
    Route::get( 'resolve', 'FileController@resolve')->name('resolve');
    Route::get( 'clear'  , 'FileController@clear')->name('clear_files');
    Route::get( '/'      , 'FileController@get')->name('files');
});

Route::prefix('exercises')->group(function(){
    Route::get( '/verbose', function(){ return view('in_development'); })->name('exercise_by_id');
    Route::get( '/'       , 'ExerciseController@get')->name('exercises');
    Route::get( '{id}'    , 'ExerciseController@get_by_id')->where(['id' => '[0-9]+'])->name('get_exercise_by_id');
});
