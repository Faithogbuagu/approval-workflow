<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function login(array $credentials)
    {
        if (!Auth::attempt($credentials)) {

            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = Auth::user();

        $token = $user
            ->createToken('authToken')
            ->accessToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token           
        ]);
    }

    public function logout($request)
    {
        $request->user()->token()->revoke();
    }
}
