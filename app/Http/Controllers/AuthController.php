<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if (Auth::attempt($credentials)) {
            $token = Auth::user()->createToken($request->userAgent(), ['*'], now()->addHours(3));
            return response()->json([
                $token,
            ]);
        }
        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);

    }

    public function register(Request $request): JsonResponse
    {

        $credentials = $request->validate([
            'email' => ['required', 'email','unique:users'],
            'password' => ['required','confirmed'],
            'name' => ['required'],
        ]);

        User::create($credentials);
        return response()->json([
            'message' => 'User created successfully',
        ],201);

    }
}
