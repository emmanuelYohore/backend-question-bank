<?php 
namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Récupère tous les users
     */
    public function getAll()
    {
        return User::all();
    }

    /**
     * Récupère un user en fonction de son id
     */
    public function getById($id)
    {
        $userId = User::findOrFail($id);
        if (empty($userId)) {
            return "user pas trouvé";
        }
        return $userId;
    }

    /**
     * Crée un nouveau user
     */
    public function create(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        return $user;
    }

    /**
     * Met à jour un user en fonction de son id
     */
    public function update($id, array $data)
    {
        $user = User::findOrFail($id);
        $user->update($data);
        return $user;
    }

    /**
     * Supprime un user en fonction de son id
     */
    public function delete($id)
    {
        return User::destroy($id);
    }
}

?>