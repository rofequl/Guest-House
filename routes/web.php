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


Route::post('login', 'Auth\LoginController@login');
Route::post('register', 'Auth\RegisterController@register');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/login', 'HomeController@login')->name('login');
Route::get('/register', 'HomeController@login')->name('register');

Route::get('/', 'HomeController@index')->name('home')->middleware('auth');

Route::get('/auth/init', 'HomeController@init');

Route::get('{path}', 'HomeController@index')->where('path','([A-z\d\-\/_.]+)?')->middleware('auth');
