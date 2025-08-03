<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = new User($request->validated());
        if ($user->save()) {
            $token = $user->createToken("authToken")->plainTextToken;
            return response()->json([
                "message" => "User registered successfully",
                "token" => $token,
                "user" => $user
            ], 201);
        }
        return response()->json([
            "message" => "Registration Failed",
        ], 400);
    }

    public function login(LoginRequest $request)
    {
        $credintials = $request->validated();
        if (!Auth::attempt($credintials)) {
            return response()->json([
                "message" => "Incorrect email or password"
            ]);
        }
        $user = Auth::user();
        $token = $user->createToken("authToken")->plainTextToken;
        return response()->json([
            "message" => "Logged in successfully",
            "user" => $user,
            "token" => $token
        ]);
    }


    public function logout(Request $request)
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();

        return response('', 204);

    }


}
