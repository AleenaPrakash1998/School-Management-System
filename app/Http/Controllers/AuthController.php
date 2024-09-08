<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Login function to authenticate user and issue token
    public function login(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Get the credentials from the request
        $credentials = $request->only('email', 'password');

        // Attempt to authenticate and generate a token
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Return the token if authentication was successful
        return response()->json(['token' => $token]);
    }

    // Logout function to invalidate the token
    public function logout()
    {
        // Invalidate the current token
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Logged out successfully']);
    }
}
