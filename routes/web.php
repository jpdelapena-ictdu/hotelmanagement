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

Route::get('/', function () {
    return redirect()->route('admin.login');
})->name('dash');

Route::get('/home', function () {
    return redirect()->route('backpack.dashboard');
});

// Auth::routes();

// Admin Login
Route::get('/admin/logout', 'Auth\AdminLoginController@adminLogout')->name('admin.logout');
Route::get('/admin/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
Route::post('/admin/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
// Route::get('/admin', 'Auth\AdminLoginController@redirect')->name('redirect-admin-login');

// Route::get('/home', 'HomeController@index')->name('home');





