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

Route::get('/', 'HomeController@dashboard');
Route::get('anotherPage', 'HomeController@cekspeed');

Route::get('registerModal','UserController@getRegister')->middleware('auth');
Route::get('userdata','UserController@getUser');
Route::get('addDomain','HomeController@addDomain');
Route::post('insertDomain','MainController@insertDomain');
Route::post('postRegister', 'UserController@postRegister')->middleware('auth');


Route::get('login','UserController@getLogin');
Route::post('postLogin','UserController@postLogin');
Route::get('logout', function(){
    Auth::logout();
    return redirect('/');
})->middleware('auth');
