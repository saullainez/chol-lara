<?php

namespace Modules\Seg\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RolesController extends Controller
{
    /**
     * SALG Función para obtener todos los roles
     * Retorna un json con los datos de los roles
     */
    public function getRoles(){
        $roles = Role::select('id', 'prefix', 'name', 'description')->where('estatus','A')->get();
        
        return response()->json(['roles' => $roles]);
    }

    /**
     * SALG Función para obtener todos los roles para el select
     * Retorna un json con los datos de los roles, en el formato que necesita el frontend para mostrarlo en el select
     */
    public function getRolesSelect(){
        $roles = Role::select('prefix as value', 'prefix as description')->where('estatus','A')
        ->get();
        return response()->json(['roles' => $roles]);
    }

    /**  
    * funcion para obtener un rol dado su id
    *  retorna un json con la informacion del rol, dado el id enviado.  
    */
    public function getRoleId($id){
        //return 'deberia estra funcionando';
        
        $role = Role::select('id','prefix','name','description')
                ->where('id', $id )->first();
        
        return response()->json(['success'=> true, 'role' => $role]);
        
    }

    /** 
    * procedimiento que crea un nuevo Rol, valida que el prefijo (prefix) no se haya ingresado con anterioridad, roleError.
    * @param request es el que trae la informacion del rol a ingresar
    * se agrega un error para saber si el nombre del rol existe (roleError)
    */ 
    public function createRole(Request $request)
    {
        $RoleInput = $request->input('role');
        
        $prefix = Role::where('prefix', $RoleInput['prefix'])->first();
        $now = Carbon::now();
        // valida si el prefijo ya existe
        if ($prefix){
            return response()->json([
                'roleError' => true,
                'message' => 'Ya existe un registro con ese prefijo asignado'
            ]);
        }else{
            //valida si el nombre del rol ya existe.
            $name = Role::where('name',$RoleInput['name'])->first();
            if ($name){
                return response()->json([
                    'roleError' => true,
                    'message' => 'Ya existe un registro con ese nombre de rol'
                ]);
            }else{

                $role = new Role();
                $role->name = $RoleInput['name'];
                $role->description = $RoleInput['description'];
                $role->prefix = $RoleInput['prefix'];
                $role->created_by = $RoleInput['created_by'];
                $role->created_at = $now;
                $role->updated_at = $now;
                $role->estatus = 'A';
                $role->save();

                return response()->json([
                    'roleError' => false,
                    'message' => 'Rol guardado con éxito'
                ]);

            }
        }

    }

    /** 
    * funcion para actualizar un rol
    *  @param request trae la informacion del rol
    * retorna un json que indica que se actualizo el rol
    * message es el string que retorna el mensaje que indica que se actualizo el registro
    */
    public function editRole(Request $request){

        $RoleInput = $request->input('role');
        $now = Carbon::now();

        $role = Role::where('id', $RoleInput['id'])->first();
        $role->name = $RoleInput['name'];
        $role->description = $RoleInput['description'];
        $role->prefix = $RoleInput['prefix'];
        $role->updated_at = $now;
        $role->save();

        return response()->json([
            'success' => true,
            'message' => 'Rol actualizado con éxito'
         ]);

    }

    /**
     * funcion que es para desactivar un rol ya ingresado
     * no se eliminan registros, unicamente se inhabilitan.
     */
    public function deactiveRole($id){
        $role = Role::where('id',$id)->first();
        $role->estatus = 'I';
        $role->updated_at = Carbon::now();
        $role->save();
        
        $roles = Role::select('id', 'prefix', 'name', 'description')
        ->where('estatus','A')
        ->get();
        return response()->json(['roles' => $roles]);

    }

}
