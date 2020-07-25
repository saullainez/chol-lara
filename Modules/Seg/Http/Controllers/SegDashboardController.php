<?php

namespace Modules\Seg\Http\Controllers;

use App\Module;
use App\Role;
use App\RoleModule;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class SegDashboardController extends Controller
{
    /**
     * SALG función que retorna información de los usuarios, módulos, permisos, roles, para mostrarla en el Dashboard
     */
    public function getDashboardInfo(){
        $usersCount = User::count();
        $rolesCount = Role::count();
        $modulesCount = Module::count();
        $rolModulesCount = RoleModule::count();
        return response()->json([
            'usersCount' => $usersCount, 
            'rolesCount' => $rolesCount,
            'modulesCount' => $modulesCount,
            'rolModulesCount' => $rolModulesCount
        ]);
    }
}
