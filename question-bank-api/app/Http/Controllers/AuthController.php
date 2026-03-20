<?php

namespace App\Http\Controllers;

use App\Enums\RoleType;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\Role;
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

            $defaultRole = Role::firstOrCreate([
                'name' => RoleType::USER->value,
            ]);

            $user->roles()->syncWithoutDetaching([$defaultRole->id]);

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

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'message' => 'Identifiants invalides'
            ], 401);
        }

        $user = JWTAuth::user();

        return response()->json([
            'access_token' => $token,
            'user' => $user,
            'message' => 'Connexion réussi'
            
        ]);
    }

    public function refreshToken()  {
        try {
            $newToken = JWTAuth::refresh(JWTAuth::getToken());
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json([
                'message' => 'Token invalide'
            ], 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json([
                'message' => 'Token expiré'
            ], 401);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Erreur lors du rafraîchissement du token'
            ], 500);
        }
       
        return response()->json([
            'access_token' => $newToken,
            'message' => 'Token rafraîchi avec succès'
        ]);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json([
            'message' => 'Déconnecté avec succès'
        ]);
    }

    public function me()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            
            if (!$user) {
                return response()->json(['message' => 'Utilisateur non trouvé'], 404);
            }
            
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Token invalide'], 401);
        }
    }
}
