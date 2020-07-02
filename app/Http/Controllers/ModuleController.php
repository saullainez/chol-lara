<?php

namespace App\Http\Controllers;

use App\Module;
use App\RoleModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModuleController extends Controller
{
    /**
     * SALG retorna todos los módulos
     */
    public function allModules(){
        $modules = Module::orderBy('name', 'ASC')->get(); 
        return response()->json($modules, 200);
    }

    /**
     * SALG retorna los módulos del usuario recibido
     * @param role role para obtener los módulos
     */
    public function roleModules($role){
        $roleModules = DB::table('rol_module as role_module')->select('module.name', 'module.description', 'module.icon', 'module.color_text',
        'module.background', 'module.uri')
        ->join('module as module', 'module.name', 'role_module.module_name')
        ->where('role_module.rol_prefix', $role)
        //->join('role as role', 'role.prefix', 'role_module.rol_prefix')
        ->where('role_module.estatus', 'A')
        ->get();
        return response()->json($roleModules, 200);
    }
    
}
