<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class LoginController extends Controller
{
    public function login(Request $request) {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:4|string'
        ]);
        if($validate->fails()) {
            return response()->json([
                'status' => 400,
                'msg' => $validate->errors()->first()
            ]);
        }else{
            $user = User::where('email', $request->email)->first();
            if(!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 400,
                    'msg' => 'Invalid credentials'
                ]);
            }else{
                $token = $user->createToken($user->email.'_Token')->plainTextToken;
                return response()->json([
                    'status' => 200,
                    'username' => $user->name,
                    'token' => $token,
                    'msg' => 'Login Successfully Auhenticated'
                ]);
            }
        }
    }

    public function logout() {
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => 200,
            'msg' => 'User Successfully Logout'
        ]);
    }
}
