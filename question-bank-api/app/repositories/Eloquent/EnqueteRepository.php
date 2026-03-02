<?php 
namespace App\Repositories\Eloquent;

use App\Models\BankItem;
use App\Models\Enquete;
use App\Models\User;
use App\Repositories\Interfaces\EnqueteRepositoryInterface;


class EnqueteRepository implements EnqueteRepositoryInterface
{
    public function getAll()
    {
        return Enquete::all();
    }

    public function getById($id)
    {
        $enqueteId = Enquete::findOrFail($id);
        if (empty($enqueteId)) {
            return "enquête pas trouvé";
        }
        return $enqueteId;
    }

    // public function getOneEnqueteForUserId($userId, $enqueteId)
    // {
    //     return Enquete::where('user_id', $userId)
    //                   ->where('id', $enqueteId)
    //                   ->firstOrFail();
    // }

    // public function getAllEnqueteForUserId($id){
    //     $enquetesUser = Enquete::all()->where('user_id', $id);
    //     return $enquetesUser;
    // }

    public function getOneEnqueteForUserId($userId, $enqueteId)
    {
        return Enquete::where('user_id', $userId)
                      ->where('id', $enqueteId)
                      ->with('bankItems')
                      ->firstOrFail();
    }

    public function getAllEnqueteForUserId($userId)
    {
        return User::findOrFail($userId)
                ->enquetes()
                ->with('bankItems')
                ->get();
    }


    public function create(array $data)
    {
        return Enquete::create($data);
    }

    public function update($id, array $data)
    {
        $enquete = Enquete::findOrFail($id);
        $enquete->update($data);
        return $enquete;
    }

    public function delete($id)
    {
        return Enquete::destroy($id);
    }
}

?>