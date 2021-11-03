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
Route::get('/dashboard/users/{id}/single', 'DashboardController@getUser')->name('dashboard.users.single');
Route::get('/dashboard/users/{id}/single/otherpage', 'DashboardController@getUserWithOtherPage')->name('dashboard.users.singleother');
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
Route::get('/dashboard/sites/{id}/categorywise', 'DashboardController@getSiteCategorywise')->name('dashboard.sites.categorywise');
Route::get('/dashboard/expense', 'DashboardController@getExpensePage')->name('dashboard.expense.index');
Route::post('/dashboard/expense/store', 'DashboardController@storeExpense')->name('dashboard.expense.store');
Route::get('/dashboard/expense/{id}/delete', 'DashboardController@deleteExpense')->name('dashboard.expense.delete');

Route::get('/dashboard/categories', 'DashboardController@getCategories')->name('dashboard.categories');
Route::post('/dashboard/categories/store', 'DashboardController@storeCategory')->name('dashboard.categories.store');
Route::post('/dashboard/categories/{id}/update', 'DashboardController@updateCategory')->name('dashboard.categories.update');

Route::get('/dashboard/creditors', 'DashboardController@getCreditors')->name('dashboard.creditors');
Route::get('/dashboard/creditors/{id}', 'DashboardController@getSingleCreditor')->name('dashboard.creditors.single');
Route::post('/dashboard/creditors/store', 'DashboardController@storeCreditor')->name('dashboard.creditors.store');
Route::post('/dashboard/creditors/{id}/update', 'DashboardController@updateCreditor')->name('dashboard.creditors.update');
Route::get('/dashboard/creditors/add/due', 'DashboardController@getAddDuePage')->name('dashboard.addduepage');
Route::post('/dashboard/creditors/due/store', 'DashboardController@storeCreditorDue')->name('dashboard.creditorsdue.store');
Route::post('/dashboard/creditors/due/{id}/update', 'DashboardController@updateCreditorDue')->name('dashboard.creditorsdue.update');
Route::get('/dashboard/creditors/due/{id}/delete', 'DashboardController@deleteCreditorDue')->name('dashboard.creditorsdue.delete');


Route::get('/dashboard/expenses/{transactiondate}/{user}', 'ExpenseController@getTodaysExpenseList')->name('dashboard.expenses.getlist');

// COMPONENTS
Route::get('/dashboard/components', 'DashboardController@getComponents')->name('dashboard.components');

// Clear Route
Route::get('/clear', ['as'=>'clear','uses'=>'DashboardController@clear']);