<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Exceptions\UserException;
use UserException as GlobalUserException;
class UserController extends Controller
{
    protected $userRepository;

    public function __construct(
        UserRepositoryInterface $userRepository
    ) {
         $this->userRepository = $userRepository;
    }

    /**
     * Récupère tous les users et recherche par email, name, surname
     */
    public function index(Request $request)
{
    try {
        $query = User::query();

        if ($search = $request->input('search')) {
            $query->where('email', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('surname', 'like', "%{$search}%");
        }

        return response()->json($query->get());
    } catch (GlobalUserException $e) {
        return response()->json([
            "message" => $e->notUsersMessage()
        ], 404);
    }
}


    /**
     * Crée un nouvel utilisateur
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['role'] = $data['role'] ?? 'user';
        try {
            $user = $this->userRepository->create($data);
            $token = JWTAuth::fromUser($user);
        } catch (GlobalUserException $e) {
            return response()->json([
                'message' => $e->notCreateUserMessage()
                ], 500);
        }
        
        return response()->json([
            'message' => 'Utilisateur créé avec succès',
            'user' => $user,
            'access_token' => $token
        ], 201);           
    }

    /**
     * Récupère un user en fonction de son id
     */
    public function show(string $id)
    {
        try {
            return response()->json($this->userRepository->getById($id));
        } catch (GlobalUserException $e) {
            return response()->json([
                "message" => $e->notUserIdMessage()
            ], 404);
        }
    }

    /**
     * Met à jour un user en fonction de son id
     */
    public function update(UpdateUserRequest $request, string $id)
    {   
        $data = $request->validated();
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        
        try {
            $user = $this->userRepository->update($id, $data);
            return response()->json([
                "message" => "User Updated.",
                "user" => $user
            ], 200);
        } catch (GlobalUserException $e) {
            return response()->json([
                "message" => $e->notUpdateUserMessage()
            ]);
        }
    }

    /**
     * Supprime un user en fonction de son id
     */
    public function destroy(string $id)
    {
        try {
            $this->userRepository->delete($id);
            return response()->json([
                'message' => 'User deleted'
            ]);
        } catch (GlobalUserException $e) {
            return response()->json([
                "message" => $e->notDeleteUserMessage()
            ]);
        }
    }
}   