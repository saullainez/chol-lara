<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class Auth extends Controller
{
    public function doLogin(Request $request){
        $email = $request->input("email");
        $pass = $request->input("password");

        $user = User::where('email', $email)->first();
        if($user){
            if (Hash::check($pass, $user->password)){
                $tokenResult = $user->createToken('Nuevo token');
                $token = $tokenResult->token;
                $expires_at = Carbon::now()->addMinutes(200);
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
