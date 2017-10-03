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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('login', 'ApiController@login');
Route::post('logout', 'ApiController@logout');
// Route::group(['middleware' => 'auth:api'], function(){
	Route::post('addEditUser', 'ApiController@addEditUser');
	Route::post('addEditProduct', 'ApiController@addEditProduct');
	Route::post('assignedProductToCollector', 'ApiController@assignedProductToCollector');
	Route::post('disburseProduct', 'ApiController@disburseProduct');
	Route::post('collectPayment', 'ApiController@collectPayment');
// });
