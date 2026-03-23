<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttachRoleToUserRequest;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\User;
use App\Repositories\Interfaces\RoleRepositoryInterface;


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
        $data = $request->validated();

        $user = User::findOrFail($data['user_id']);

        $user->roles()->syncWithoutDetaching($data['role_ids']);

        $attachedRoles = $user->roles()->whereIn('roles.id', $data['role_ids'])->get();

        return response()->json([
            'message' => 'Rôles ajoutés avec succès.',
            'user_id' => $user->id,
            'attached_roles' => $attachedRoles,
            'user' => $user->load('roles')
        ]);
    }

    public function detachRoleFromUser(AttachRoleToUserRequest $request)
    {
        $data = $request->validated();

        $user = User::findOrFail($data['user_id']);

        $user->roles()->detach($data['role_ids']);

        return response()->json([
            'message' => 'Rôles supprimés avec succès.',
            'user_id' => $user->id,
            'detached_role_ids' => $data['role_ids'],
            'user' => $user->load('roles')
        ]);
    }
}

