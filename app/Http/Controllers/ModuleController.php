<?php

namespace App\Http\Controllers;

use App\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**SALG retorna todos los módulos
     * 
     */
    public function allModules(){
        $modules = Module::orderBy('name', 'ASC')->get(); 
        return response()->json($modules, 200);
    }
    
}
