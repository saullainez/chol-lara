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

        //SALG Obtener los permisos de los módulos
        Route::get('menu_permission/{module}/{role}', 'MenuController@getMenuPermission');

        //SALG Rutas para usuarios
        //Obtener los usuarios
        Route::get('users', 'UsersController@getUsers');
        //Desactivar los usuarios
        Route::delete('users/{id}', 'UsersController@deactivateUser');
        //Obtener dato de un usuario
        Route::get('user/{id}', 'UsersController@getUser');
        //Guardar un usuario
        Route::post('users', 'UsersController@saveUser');
        //Editar un usuario
        Route::put('users', 'UsersController@editUser');

        //SALG Rutas para roles
        //Obtener los roles
        Route::get('roles-select', 'RolesController@getRolesSelect');
        Route::get('roles', 'RolesController@getRoles');

        //SALG Rutas para opciones de menú
        Route::get('permission_menu', 'PermissionMenuController@getPermissionMenu');
        //Obtener las opciones de menú según el rol y el módulo
        Route::get('permission_menu_role_module/{role}/{module}', 'PermissionMenuController@getPermissionMenuRoleModule');
        //guardar las opciones de menú para un rol y un módulo
        Route::post('permission_menu', 'PermissionMenuController@savePermissionMenu');

        //SALG Rutas para módulos
        //Obtener los módulos
        Route::get('modules-select', 'SegModulesController@getModulesSelect');
    });
});