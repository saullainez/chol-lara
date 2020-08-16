<?php

namespace Modules\Seg\Http\Controllers;

use App\Module;
use App\RoleModule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class RoleModuleController extends Controller
{
    /**
     * SALG Función para obtener todos los datos de permisos de módulos a roles
     * Retorna un json con los datos de los permisos
     */
    public function getRoleModule(){
        $role_module = RoleModule::select('rol_module.id', 'mod.description as module_name', 'rol_prefix as rol_name')->where('estatus', 'A')
        ->join('module as mod', 'mod.name', 'rol_module.module_name')
        ->orderBy('rol_module.id')
        ->get();
        return response()->json(['role_module' => $role_module]);
    }

    /**
    * SALG Función para obtener los permisos que han sido asignados y lao que no, según el rol
    * Retorna un json con los datos de los permisos asignados y no asignados, según el rol
    */
    public function getPermissionRoleModule($role){
        $role_moduleA = Module::select('name', 'description', 'module.id')
        ->join('rol_module as rm', 'rm.module_name', 'module.name')
        ->where('rm.rol_prefix', $role)
        ->where('rm.estatus', 'A')->get();
        

        $role_moduleNA = Module::selectRaw("DISTINCT name, description, module.id")
        ->leftJoin('rol_module as rm', 'rm.module_name', 'module.name')
        ->where(function($query) use ($role){
            $query->where('rm.module_name', null)
            ->orWhere('rm.estatus', '!=', 'A');
        })  
        ->get();
        return response()->json(['role_moduleA' => $role_moduleA, 'role_moduleNA' => $role_moduleNA]);
    }

    /**
     * SALG Función para guardar los permisos
     * @param request vienen los datos del rol y los permisos a asignar
     * Retorna permissionError = true si hay algún problema al guardar
     * Retorna permissionError = false si el permiso se creó exitosamente
     * Retorna message, que contiene el mensaje para mostrar al usuario en el frontend
     */
    public function saveRoleModule(Request $request){
        $role = $request->input('role');
        $user = $request->input('user');
        $assigned = $request->input('assigned');
        $delete = [];
        $now = Carbon::now();
        $unassigned =  $request->input('toAssing');
        $role_module = RoleModule::select('mod.id')
        ->join('module as mod', 'mod.name', 'rol_module.module_name')
        ->where('rol_prefix', $role)
        ->where('estatus', 'A')->get();
        foreach($role_module as $rm){
            foreach($assigned as $key => $element){
                if($rm->id == $element){
                    unset($assigned[$key]);
                }
            }
            foreach($unassigned as $un){
                if($rm->id == $un){
                    array_push($delete, $rm->id);
                }
            }
        }

        if(count($assigned) > 0){
            foreach($assigned as $permission){
                $module = Module::where('id', $permission)->first();
                $role_module_unactive = RoleModule::where('rol_prefix', $role)
                ->where('module_name', $module->name)
                ->where('estatus', 'I')->first();
                if($role_module_unactive){
                    $role_module_unactive->estatus='A';
                    $role_module_unactive->updated_at=$now;
                    $role_module_unactive->save();
                }else{
                    $new_role_module = new RoleModule();
                    $new_role_module->module_name = $module->name;
                    $new_role_module->rol_prefix = $role;
                    $new_role_module->estatus = 'A';
                    $new_role_module->created_by = $user;
                    $new_role_module->created_at = $now;
                    $new_role_module->updated_at = $now;
                    $new_role_module->save();
                }

            }
        }

        if(count($delete)>0){
            foreach($delete as $d){
                $module = Module::where('id', $d)->first();
                $role_module_deactivate = RoleModule::where('rol_prefix', $role)
                ->where('module_name', $module->name)->first();
                $role_module_deactivate->estatus = 'I';
                $role_module_deactivate->updated_at = $now;
                $role_module_deactivate->save();
            }
        }

        return response()->json([
            'permissionError' => false,
            'message' => 'Permisos guardados con éxito',
        ]);
        
    }
}
