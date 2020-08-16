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

        //obtener un rol dado su id
        Route::get('roles/{id}','RolesController@getRoleId');
        //guarda un nuevo rol
        Route::post('roles', 'RolesController@CreateRole');
        //elimina un rol
        
        Route::delete('roles/{id}', 'RolesController@deactiveRole');
        //actualiza un rol
        Route::put('roles','RolesController@editRole');


        //SALG Rutas para opciones de menú
        Route::get('permission_menu', 'PermissionMenuController@getPermissionMenu');
        //Obtener las opciones de menú según el rol y el módulo
        Route::get('permission_menu_role_module/{role}/{module}', 'PermissionMenuController@getPermissionMenuRoleModule');
        //guardar las opciones de menú para un rol y un módulo
        Route::post('permission_menu', 'PermissionMenuController@savePermissionMenu');

        //SALG Rutas para módulos
        //Obtener los módulos
        Route::get('modules-select', 'SegModulesController@getModulesSelect');

        //SALG Rutas para permisos de módulos a roles
        //Obtener todos permisos de módulos a roles
        Route::get('role_module', 'RoleModuleController@getRoleModule');
        //Obtener los permisos de módulos a roles según el rol
        Route::get('permission_role_module/{role}', 'RoleModuleController@getPermissionRoleModule');
        //guardar los permisos a módulos para un rol
        Route::post('role_module', 'RoleModuleController@saveRoleModule');
    });
});