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
Route::get('/dashboard/users/{id}/delete', 'DashboardController@deleteUser')->name('dashboard.users.delete');

Route::get('/dashboard/balance', 'DashboardController@getBalance')->name('dashboard.balance');
Route::post('/dashboard/balance/store', 'DashboardController@storeBalance')->name('dashboard.balance.store');
Route::get('/dashboard/balance/{id}/delete', 'DashboardController@deleteBalance')->name('dashboard.balance.delete');

Route::get('/dashboard/sites', 'DashboardController@getSites')->name('dashboard.sites');
Route::post('/dashboard/sites/store', 'DashboardController@storeSite')->name('dashboard.sites.store');
Route::post('/dashboard/sites/{id}/update', 'DashboardController@updateSite')->name('dashboard.sites.update');
Route::get('/dashboard/sites/{id}/delete', 'DashboardController@deleteSite')->name('dashboard.sites.delete');
Route::get('/dashboard/sites/{id}', 'DashboardController@getSingleSite')->name('dashboard.sites.single');

// COMPONENTS
Route::get('/dashboard/components', 'DashboardController@getComponents')->name('dashboard.components');

// Clear Route
Route::get('/clear', ['as'=>'clear','uses'=>'DashboardController@clear']);