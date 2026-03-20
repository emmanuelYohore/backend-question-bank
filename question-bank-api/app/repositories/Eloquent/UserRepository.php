<?php 
namespace App\Repositories\Eloquent;

use App\Enums\RoleType;
use App\Models\Role;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function getAll()
    {
        return User::all();
    }

    public function getById($id)
    {
        $userId = User::findOrFail($id);
        if (empty($userId)) {
            return "user pas trouvé";
        }
        return $userId;
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $roleName = $data['role'] ?? RoleType::USER->value;
            unset($data['role']);

            $data['password'] = Hash::make($data['password']);
            $user = User::create($data);

            $role = Role::firstOrCreate(['name' => $roleName]);
            $user->roles()->syncWithoutDetaching([$role->id]);

            return $user->load('roles');
        });
    }

    public function update($id, array $data)
    {
        $user = User::findOrFail($id);
        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        return User::destroy($id);
    }
}

?>