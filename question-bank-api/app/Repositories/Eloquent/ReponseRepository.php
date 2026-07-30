<?php 
namespace App\Repositories\Eloquent;

use App\Models\Reponse;
use App\Repositories\Interfaces\ReponseRepositoryInterface;

class ReponseRepository implements ReponseRepositoryInterface
{
    public function getAll()
    {
        return Reponse::all();
    }
    
    public function getById($id)
    {
        $reponseId = Reponse::findOrFail($id);
        if (empty($reponseId)) {
            return "Reponse pas trouvé";
        }
        return $reponseId;
    }

    public function create(array $data)
    {
        
        return Reponse::create($data);
    }

    public function update($id, array $data)
    {
        $reponse = Reponse::findOrFail($id);
        $reponse->update($data);
        return $reponse;
    }

    public function delete($id)
    {
        return Reponse::destroy($id);
    }
}

?>