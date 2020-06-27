<?php

namespace App\Http\Controllers;

use App\SysParam;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class Auth extends Controller
{
    /**
     * SALG Función para Iniciar sesión en el sistema, crea el token y la sesión
     * @param $request vienen los datos de email y contraseña del usuario
     * Retorna validate = false si no se puede iniciar sesión
     * Retorna un json con los datos de la sesión, si el login fue correcto
     */

    public function doLogin(Request $request){
        //SALG obtener los parámetros para validar el login
        $email = $request->input("email");
        $pass = $request->input("password");
        //SALG obtenemos el usuario, si no existe, retornamos validate = false
        $user = User::where('email', $email)->first();
        if($user){
            //SALG si el usuario existe, comprobamos si las contraseñas son iguales, si no, retornamos validate = false
            if (Hash::check($pass, $user->password)){
                $sys_param = SysParam::where('id', 1)->first();//SALG se obtiene el objeto con los parámetros del sistema
                /*SALG Se crea el token, la sesión y se establece el tiempo de expiración del token, según el parámetro
                que está en los parámetros del sistema*/
                $tokenResult = $user->createToken('Nuevo token');
                $token = $tokenResult->token;
                $expires_at = Carbon::now()->addMinutes($sys_param->session_time);
                $token->expires_at = $expires_at;
                $token->save();
                Session::put('user_session', $user->name);
                return response()->json([
                    'validate' => true,
                    'token' => $token->id,
                    'expires_at' => Carbon::parse($expires_at),
                    'username' => $user->name,
                    'email' => $user->email
                ], 200);
            }else{
                return response()->json(['validate' => false]);
            }
        }else{
            return response()->json(['validate' => false]);
        }
        
    }

    /**
     * SALG Función para cerrar sesión
     * @param $request email del usuario que quiere cerrar sesión
     * Se establece como revoke = true todos los tokens de acceso que tenga el usuario que hace la petición
     */
    public function doLogout(Request $request){
        $user = User::where('email', $request->input("email"))->first();
        DB::table('oauth_access_tokens')
        ->where('user_id', $user->id)
        ->update([
            'revoked' => true
        ]);

        return response()->json(null, 204);
    }
}
