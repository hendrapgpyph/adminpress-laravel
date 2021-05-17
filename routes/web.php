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
    return redirect("/home");
});

Auth::routes();
Route::group(['middleware' => ['web','auth']], function (){

	// Home
	Route::prefix('home')->group(function () {
	    Route::get('/', 'HomeController@index')->name('home');
	});

    // Users
	Route::prefix('users')->group(function () {
		Route::get('/','UserCon@index')->name('users');
		Route::get('/jsonListStaff', 'UserCon@jsonListStaff');
		Route::get('/form', 'UserCon@form');
		Route::post('/processForm','UserCon@processForm');
		Route::get('/hapusStaff','UserCon@hapusStaff');
		Route::get('/setThemeUser', 'UserCon@setSandboxProduction');
		Route::post('/reset_token', 'UserCon@resetToken');
	});

	// Profile
	Route::prefix('profile')->group(function() {
		Route::get('/','UserCon@form')->name('profile');
	});

	// Transaction
	Route::prefix('transaction')->group(function() {
		Route::get('/','TransactionController@index');
		Route::get('/jsonListTransaksi', 'TransactionController@jsonListTransaksi');
	});
});
