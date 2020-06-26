<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Auth extends Controller
{
    public function doLogin(Request $request){
        $user = $request->input("username");
        $pss = $request->input("password");
        
    }
}
