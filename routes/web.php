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
Route::get('/', 'GameController@index');
Route::get('/newgame', 'GameController@startNewGame');
Route::get('/feed', 'GameController@feedToRandom');
Route::get('/session', 'GameController@session');