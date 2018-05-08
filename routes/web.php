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

Route::view('/login', 'pages/login')->name('login');

Route::view('/settings', 'pages/settingshome')->middleware('authWeb');

Route::view('/users', 'pages/users')->middleware('authWeb');

