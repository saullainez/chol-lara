<?php

namespace Modules\Seg\Http\Controllers;

use App\Module;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class SegModulesController extends Controller
{
    /**
     * SALG Función para obtener todos los módulos para el select
     * Retorna un json con los datos de los módulos, en el formato que necesita el frontend para mostrarlo en el select
     */
    public function getModulesSelect(){
        $modules = Module::select('name as value', 'description')->get();
        return response()->json(['modules' => $modules]);
    }
}
