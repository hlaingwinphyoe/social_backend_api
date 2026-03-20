<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);

        $accessToken = $user->createToken('access_token')->plainTextToken;

        return response()->json([
            'message' => 'User created successfully',
            'access_token' => $accessToken,
            'user' => new UserResource($user)
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        $accessToken = $user->createToken('access_token')->plainTextToken;

        return response()->json([
            'message' => 'User logged in successfully',
            'access_token' => $accessToken,
            'user' => new UserResource($user)
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'User logged out successfully',
        ], 200);
    }

    public function myProfile(): JsonResponse
    {
        $user = Auth::user();

        $user->loadCount(['posts', 'reactions', 'comments']);

        return response()->json([
            'message' => 'Profile retrieved successfully',
            'user' => new UserResource($user)
        ], 200);
    }
}
