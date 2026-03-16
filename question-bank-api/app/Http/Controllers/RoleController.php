<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttachRoleToUserRequest;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\User;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Request;

class RoleController extends Controller
{
    protected $roleRepository;

    public function __construct(
        RoleRepositoryInterface $roleRepository
    ) {
         $this->roleRepository = $roleRepository;
    }

    public function index()
    {
        return response()->json($this->roleRepository->getAll());
    }

    public function store(StoreRoleRequest $request)
    {
        $data = $request->validated();
        
        $role = $this->roleRepository->create($data);
        return response()->json([
            "message"=> "role crée avec succès",
            "role"=> $role
            ], 201);
    }


    public function show(string $id)
    {
         return response()->json($this->roleRepository->getById($id));
    }

    
    public function update(UpdateRoleRequest $request, string $id)
    {   
        $data = $request->validated();
     
        $role = $this->roleRepository->update($id, $data);
        
        return response()->json([
            "message" => "Role Updated.",
            "role" => $role
        ], 200);
    }

   
    public function destroy(string $id)
    {
        $this->roleRepository->delete($id);
        return response()->json([
            'role deleted' 
            ]);
    }

    public function addRoleToUser(AttachRoleToUserRequest $request)
    {
        $request->validate();

        $user = User::findOrFail($request->user_id);

        $user->roles()->syncWithoutDetaching([$request->role_id]);

        return response()->json([
            'message' => 'Rôle ajouté avec succès.',
            'user' => $user->load('roles')
        ]);
    }

    public function removeRoleFromUser(AttachRoleToUserRequest $request)
    {
        $request->validate();

        $user = User::findOrFail($request->user_id);

        $user->roles()->detach($request->role_id);

        return response()->json([
            'message' => 'Rôle supprimé avec succès.',
            'user' => $user->load('roles')
        ]);
    }
}

