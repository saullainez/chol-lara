<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['middlweare' => 'cors'], function (){
    //SALG Rutas de inicio y cierre de sesión
    Route::post('login', 'Auth@doLogin');
    Route::post('logout', 'Auth@doLogout');
    //SALG MÓDULOS
    Route::get('modules', 'ModuleController@allModules'); //Obtener todos los módulos
    Route::get('modules/{role}', 'ModuleController@roleModules'); //Obtener los módulos para ese rol
});
