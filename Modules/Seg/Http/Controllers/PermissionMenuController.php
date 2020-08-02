<?php

namespace Modules\Seg\Http\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Seg\Entities\Menu;
use Modules\Seg\Entities\PermissionMenu;
use Modules\Seg\Entities\SubMenu;

class PermissionMenuController extends Controller
{
    /**
    * SALG Función para obtener todos los permisos de opciones de menú
    * Retorna un json con los datos de los permisos a opciones de menú
    */
    public function getPermissionMenu(){
        $permissions_menu = PermissionMenu::select('permission_menu.id', 'permission_menu.mod_prefix as module', 'sm.description as sub_menu_desc', 'm.description as menu_desc', 'permission_menu.rol_prefix as role')
        ->join('sub_menu as sm', 'sm.id', 'permission_menu.sub_menu_id')
        ->join('menu as m', 'm.id', 'sm.id_menu')
        ->where('estatus', 'A')->get();
        return response()->json(['permissions_menu' => $permissions_menu]);
    }

    /**
    * SALG Función para obtener las opciones de menú que han sido asignadas y las que no, según el módulo y el rol
    * Retorna un json con los datos de las opciones de menú asignadas y no asignadas, según el rol y el módulo
    */
    public function getPermissionMenuRoleModule($role, $module){
        $permissions_menuA = SubMenu::select('sub_menu.id', 'sub_menu.id_menu', 'sub_menu.description as sub_menu_desc', 'sub_menu.icon as sub_menu_icon')
        ->join('menu as m', 'm.id', 'sub_menu.id_menu')
        ->join('permission_menu as pm', 'pm.sub_menu_id', 'sub_menu.id')
        ->where('m.mod_prefix', $module)
        ->where('pm.rol_prefix', $role)
        ->where('pm.estatus', 'A')->get();
        

        $permissions_menuNA = SubMenu::selectRaw("DISTINCT sub_menu.id, sub_menu.id_menu, sub_menu.description as sub_menu_desc, sub_menu.icon as sub_menu_icon")
        ->join('menu as m', 'm.id', 'sub_menu.id_menu')
        ->leftJoin('permission_menu as pm', 'pm.sub_menu_id', 'sub_menu.id')
        ->where('m.mod_prefix', $module)
        ->where(function($query) use ($role){
            $query->where('pm.sub_menu_id', null)
            ->orWhere('pm.estatus', '!=', 'A');
        })  
        ->get();
        return response()->json(['permissions_menuA' => $permissions_menuA, 'permissions_menuNA' => $permissions_menuNA]);
    }

    /**
     * SALG Función para guardar los permisos
     * @param request vienen los datos del módulo, el rol y los permisos a asignar
     * Retorna permissionMenuError = true si hay algún problema al guardar
     * Retorna permissionMenuError = false si el permiso se creó exitosamente
     * Retorna message, que contiene el mensaje para mostrar al usuario en el frontend
     */
    public function savePermissionMenu(Request $request){
        $role = $request->input('role');
        $module = $request->input('module');
        $user = $request->input('user');
        $assigned = $request->input('assigned');
        $delete = [];
        $now = Carbon::now();
        $unassigned =  $request->input('toAssing');
        $permissions_menu = PermissionMenu::select('sub_menu_id')
        ->where('mod_prefix', $module)->where('rol_prefix', $role)
        ->where('estatus', 'A')->get();
        foreach($permissions_menu as $pm){
            foreach($assigned as $key => $element){
                if($pm->sub_menu_id == $element){
                    unset($assigned[$key]);
                }
            }
            foreach($unassigned as $un){
                if($pm->sub_menu_id == $un){
                    array_push($delete, $pm->sub_menu_id);
                }
            }
        }

        if(count($assigned) > 0){
            foreach($assigned as $permission){
                $permission_menu_unactive = PermissionMenu::where('sub_menu_id', $permission)
                ->where('mod_prefix', $module)
                ->where('rol_prefix', $role)
                ->where('estatus', 'I')->first();
                if($permission_menu_unactive){
                    $permission_menu_unactive->estatus='A';
                    $permission_menu_unactive->save();
                }else{
                    $new_permission_menu = new PermissionMenu();
                    $new_permission_menu->mod_prefix = $module;
                    $new_permission_menu->sub_menu_id = $permission;
                    $new_permission_menu->rol_prefix = $role;
                    $new_permission_menu->estatus = 'A';
                    $new_permission_menu->created_by = $user;
                    $new_permission_menu->created_at = $now;
                    $new_permission_menu->updated_at = $now;
                    $new_permission_menu->save();
                }

            }
        }

        if(count($delete)>0){
            foreach($delete as $d){
                $permission_menu_deactivate = PermissionMenu::where('sub_menu_id', $d)->where('mod_prefix', $module)
                ->where('rol_prefix', $role)->first();
                $permission_menu_deactivate->estatus = 'I';
                $permission_menu_deactivate->save();
            }
        }

        return response()->json([
            'permissionMenuError' => false,
            'message' => 'Permisos guardados con éxito',
        ]);
        
    }

    
}
