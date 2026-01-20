<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(
        UserRepositoryInterface $userRepository
    ) {
         $this->userRepository = $userRepository;
    }

    public function index()
    {
        return response()->json($this->userRepository->getAll());
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        
        $user = $this->userRepository->create($data);
        return response()->json([
            "message"=> "user crée avec succès",
            "user"=> $user
            ], 201);
    }


    public function show(string $id)
    {
         return response()->json($this->userRepository->getById($id));
    }

    
    public function update(UpdateUserRequest $request, string $id)
    {   
        $data = $request->validated();
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        
        $user = $this->userRepository->update($id, $data);
        
        return response()->json([
            "message" => "User Updated.",
            "user" => $user
        ], 200);
    }

   
    public function destroy(string $id)
    {
        $this->userRepository->delete($id);
        return response()->json([
            'user deleted' 
            ]);
    }
}
