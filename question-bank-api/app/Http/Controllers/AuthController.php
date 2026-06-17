<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
     public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    /**
     *fonction pour l'inscription d'un utilisateur
     */
    public function register(StoreUserRequest $request)
    {
        $data = $request->validated(); 
        $data['role'] = $data['role'] ?? 'user';   
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

    /**
     * fonction pour la connexion d'un utilisateur
     */
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

    /**
     * fonction pour le rafraîchissement du token
     */
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

    /**
     * fonction pour la déconnexion d'un utilisateur
     */
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json([
            'message' => 'Déconnecté avec succès'
        ]);
    }

    /**
     * fonction pour récupérer les informations de l'utilisateur connecté
     */
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

    /**
     * fonction pour la réinitialisation du mot de passe
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
    $email = $request->validated()['email'];

    // Génère un token sécurisé et le stocke (hashé)
    $token = Str::random(64);

    DB::table('AQUALI_password_reset_tokens')->updateOrInsert(
        ['email' => $email],
        [
            'token'      => hash('sha256', $token),
            'created_at' => now(),
        ]
    );

    $user      = $this->userRepository->findByEmail($email);
    $resetUrl  = env('FRONTEND_URL') . '/reset-password?token=' . $token . '&email=' . urlencode($email);

    Mail::to($email)->send(new ResetPasswordMail($resetUrl, $user->name));

    return response()->json([
        'message' => 'Un lien de réinitialisation a été envoyé à votre adresse email.'
    ]);
}


public function resetPassword(ResetPasswordRequest $request)
{
    $data = $request->validated();

    $record = DB::table('AQUALI_password_reset_tokens')
        ->where('email', $data['email'])
        ->first();

    // Vérifie que le token existe, correspond et n'a pas expiré (15 min)
    if (
        !$record
        || !hash_equals($record->token, hash('sha256', $data['token']))
        || now()->diffInMinutes($record->created_at) > 15
    ) {
        return response()->json([
            'message' => 'Token invalide ou expiré.'
        ], 422);
    }

    $user = $this->userRepository->findByEmail($data['email']);
    $this->userRepository->update($user->id, [
        'password' => Hash::make($data['password']),
    ]);

    // Supprime le token utilisé
    DB::table('AQUALI_password_reset_tokens')->where('email', $data['email'])->delete();

    return response()->json([
        'message' => 'Mot de passe réinitialisé avec succès.'
    ]);
}
}
