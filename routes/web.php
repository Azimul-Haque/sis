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

Route::get('/', function () { return redirect()->route('dashboard.index'); });
Auth::routes();

Route::get('/dashboard', 'DashboardController@index')->name('dashboard.index');
Route::get('/dashboard/users', 'DashboardController@getUsers')->name('dashboard.users');
Route::post('/dashboard/users/store', 'DashboardController@storeUser')->name('dashboard.users.store');
Route::post('/dashboard/users/{id}/update', 'DashboardController@updateUser')->name('dashboard.users.update');

Route::get('/dashboard/balance', 'DashboardController@getBalance')->name('dashboard.balance');
Route::get('/dashboard/sites', 'DashboardController@getSites')->name('dashboard.sites');

// COMPONENTS
Route::get('/dashboard/components', 'DashboardController@getComponents')->name('dashboard.components');

