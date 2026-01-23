<?php 
namespace App\Repositories\Eloquent;

use App\Models\ModaliteReponse;
use App\Repositories\Interfaces\ModaliteReponseRepositoryInterface;

class ModaliteReponseRepository implements ModaliteReponseRepositoryInterface
{
    public function getAll()
    {
        return ModaliteReponse::all();
    }

    public function getById($id)
    {
        $modaliteReponseId = ModaliteReponse::findOrFail($id);
        if (empty($modaliteReponseId)) {
            return "bank Item pas trouvé";
        }
        return $modaliteReponseId;
    }

    public function create(array $data)
    {
        
        return ModaliteReponse::create($data);
    }

    public function update($id, array $data)
    {
        $modaliteReponse = ModaliteReponse::findOrFail($id);
        $modaliteReponse->update($data);
        return $modaliteReponse;
    }

    public function delete($id)
    {
        return ModaliteReponse::destroy($id);
    }
}

?>