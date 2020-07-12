<?php

namespace Modules\Seg\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class UsersController extends Controller
{
    public function getUsers(){
        $users = User::select('id', 'name', 'email', 'role_prefix', 'username')
        ->where('estatus', 'A')->get();
        return response()->json([
            'users' => $users
         ]);
    }
}
