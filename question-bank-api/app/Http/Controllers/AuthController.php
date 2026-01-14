<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
     public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}


    public function register(StoreUserRequest $request)
    {
        $data = $request->validated();
        
        try {
            $user = $this->userRepository->create($data);
            $token = JWTAuth::fromUser($user);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to register, please try again'
                ], 500);
        }
        
        return response()->json([
            'message' => 'Utilisateur créé avec succès',
            'user' => $user,
            'access_token' => $token
        ], 201);
    }

    public function login(StoreUserRequest $request)
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
