<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
     public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'surname' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|string|in:user,admin'
        ]);
           
        $data['password'] = Hash::make($data['password']);

        try {

            $user = $this->userRepository->create($data);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to register, please try again'
                ], 500);
        }
        return response()->json([
            'message' => 'utilisateur créer avec succès',
            'user' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'message' => 'Identifiants invalides'
            ], 401);
        }

        return response()->json([
            'access_token' => $token,
            'message' => 'Connexion réussi'
            
        ]);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json([
            'message' => 'Déconnecté avec succès'
        ]);
    }
}
