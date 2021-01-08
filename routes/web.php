<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () { return redirect()->route('dashboard.inedx'); });
Auth::routes();

Route::get('/dashboard', 'DashboardController@index')->name('dashboard.inedx');
Route::get('/dashboard/users', 'DashboardController@getUsers')->name('dashboard.users');
Route::get('/dashboard/balance', 'DashboardController@getBalance')->name('dashboard.balance');
Route::get('/dashboard/sites', 'DashboardController@getSites')->name('dashboard.sites');

// COMPONENTS
Route::get('/dashboard/components', 'DashboardController@getComponents')->name('dashboard.components');

