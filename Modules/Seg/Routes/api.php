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

Route::middleware('auth:api')->get('/seg', function (Request $request) {
    return $request->user();
});

Route::group(['middlweare' => 'cors'], function (){
    Route::prefix('seg')->group(function(){
        //SALG Obtener información para el dashboard
        Route::get('dash_info', 'SegDashboardController@getDashboardInfo');
    });
});