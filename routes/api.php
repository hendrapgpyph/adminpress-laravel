<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/', function (Request $request) {
//     return $request->user();
//     Route::get('/test','UserCon@testAPI');
// });

Route::group(['middleware' => ['auth:api']], function (){
    // BRIVA
	Route::prefix('briva')->group(function () {
	    Route::post('/{type}/create', 'BrivaController@create')->name('briva.create');
	    Route::post('/{type}/update', 'BrivaController@update')->name('briva.update');
	    Route::post('/{type}/status/paid', 'BrivaController@paidInvoice')->name('briva.invoice.paid');
	    Route::post('/{type}/status/unpaid', 'BrivaController@unpaidInvoice')->name('briva.invoice.unpaid');
	    Route::post('/{type}/delete', 'BrivaController@deleteInvoice')->name('briva.invoice.delete');
        Route::get('/{type}/getUpdate', 'BrivaController@getUpdate')->name('briva.getupdate');
        Route::post('/{type}/getDetail', 'BrivaController@getDetail')->name('briva.getDetail');
	});
});

Route::post('/test-post', 'BrivaController@testPost');