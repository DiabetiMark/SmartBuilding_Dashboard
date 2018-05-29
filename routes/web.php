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

Route::view('/', 'pages/home')->middleware('authWeb')->name('home');
Route::view('/overview', 'pages/overview');
Route::get('/overview/{room}', 'RoomController@index');

Route::view('/settings', 'pages/settings')->middleware('authWeb');

Route::view('/users', 'pages/users')->middleware('authWeb');


Route::view('/login', 'pages/login/login')->name('login');
Route::view('/login/forget', 'pages/login/passwordForget');
Route::get('/login/wachtwoord/{user_id}/{email}/{hash}', 'PagesController@resetPassword');

