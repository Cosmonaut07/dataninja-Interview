<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request){
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if (Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'logged in successfully',
                'token' => Auth::user()->createToken()->access_token
            ]);
        }
        return response()->json([
            'message' => 'Invalid credentials',
        ]);

    }

    public function register(Request $request){

        $credentials = $request->validate([
            'email' => ['required', 'email','unique:users'],
            'password' => ['required'],
            'confirm_password' => ['required', 'same:password'],
            'name' => ['required'],
        ]);

        User::create($credentials);
        return response()->json([
            'message' => 'User created successfully',
        ]);

    }
}
