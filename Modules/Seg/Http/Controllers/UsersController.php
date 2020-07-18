<?php

namespace Modules\Seg\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * SALG Función para obtener todos los usuarios
     * Retorna un json con los datos de los usuarios que están activos
     */
    public function getUsers(){
        $users = User::select('id', 'name', 'email', 'role_prefix', 'username')
        ->where('estatus', 'A')->get();
        return response()->json([
            'users' => $users
         ]);
    }

    /**
     * SALG Función para eliminar (desactivar) un usuario y obtener todos los usuarios activos
     * @param id id del usuario que se desea eliminar
     * Retorna un json indicando si se eliminó el usuario, y los datos de los usuarios activos en el sistema
     */
    public function deactivateUser($id) {
        $user = User::where('id', $id)->first();
        $user->estatus = 'I';
        $user->save();
        $users = User::select('id', 'name', 'email', 'role_prefix', 'username')
        ->where('estatus', 'A')->get();
        return response()->json([
            'success' => true,
            'users' => $users
         ]);
    }

    /**
     * SALG Función para obtener los datos de un usuario
     * @param id id del usuario que se desea obtener información
     * Retorna un json con los datos del usuario 
     */
    public function getUser($id) {
        $user = User::select('id', 'name', 'email', 'role_prefix', 'username')
        ->where('id', $id)->first();
        return response()->json([
            'success' => true,
            'user' => $user
         ]);
    }

    /**
     * SALG Función para guardar un usuario
     * @param request vienen los datos del usuario y el rol
     * Retorna userError = true si hay algún problema por repetición de username o email al crear el usuario
     * Retorna userError = false si el usuario se creó exitosamente
     * Retorna message, que contiene el mensaje para mostrar al usuario en el frontend
     */
    public function saveUser(Request $request){
        $userInput = $request->input('user');
        $now = Carbon::now();
        $username = User::where('username', $userInput['username'])->first();
        if($username){
            return response()->json([
                'userError' => true,
                'message' => 'Ya existe un registro con ese nombre de usuario'
            ]);
        }else{
            $email = User::where('email', $userInput['email'])->first();
            if($email) {
                return response()->json([
                    'userError' => true,
                    'message' => 'Ya existe un registro con ese correo electrónico'
                ]);
            }else {
                $user = new User();
                $user->name = $userInput['name'];
                $user->email = $userInput['email'];
                $user->username = $userInput['username'];
                $user->role_prefix = $request->input('rolPrefix');
                $user->estatus = 'A';
                $user->created_at = $now;
                $user->updated_at = $now;
                $user->password = Hash::make('password');;
                $user->save();
                return response()->json([
                    'userError' => false,
                    'message' => 'Usuario guardado con éxito'
                ]);
            }

        }
    }

    /**
     * SALG Función para editar un usuario
     * @param request vienen los datos del usuario y el rol
     * Retorna un json indicando si se editó el usuario
     * Retorna message, que contiene el mensaje para mostrar al usuario en el frontend
     */
    public function editUser(Request $request) {
        $userInput = $request->input('user');
        $user = User::where('username', $userInput['username'])->first();
        $user->name = $userInput['name'];
        $user->email = $userInput['email'];
        $user->username = $userInput['username'];
        $user->role_prefix = $request->input('rolPrefix');
        $user->updated_at = Carbon::now();
        $user->save();
        return response()->json([
            'success' => true,
            'message' => 'Usuario actualizado con éxito'
         ]);
    }
}
