<?php

namespace Modules\Seg\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class RolesController extends Controller
{
    /**
     * SALG Función para obtener todos los roles
     * Retorna un json con los datos de los roles
     */
    public function getRoles(){
        $roles = Role::select('id', 'prefix', 'name', 'description')->get();
        return response()->json(['roles' => $roles]);
    }

    /**
     * SALG Función para obtener todos los roles para el select
     * Retorna un json con los datos de los roles, en el formato que necesita el frontend para mostrarlo en el select
     */
    public function getRolesSelect(){
        $roles = Role::select('prefix as value', 'prefix as description')->get();
        return response()->json(['roles' => $roles]);
    }
}
