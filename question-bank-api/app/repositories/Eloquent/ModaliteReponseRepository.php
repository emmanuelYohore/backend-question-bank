<?php 
namespace App\Repositories\Eloquent;

use App\Models\ModaliteReponse;
use App\Repositories\Interfaces\ModaliteReponseRepositoryInterface;

class ModaliteReponseRepository implements ModaliteReponseRepositoryInterface
{
    /**
     * Récupère toutes les modalités de réponse avec leur format de réponse associé
     */
    public function getAll()
    {
        return ModaliteReponse::with('formatReponse')->get();
    }

    /**
     * Récupère une modalité de réponse en fonction de son id avec son format de réponse associé
     */
    public function getById($id)
    {
        $modaliteReponseId = ModaliteReponse::with('formatReponse')->findOrFail($id);
        if (empty($modaliteReponseId)) {
            return "Modalité de réponse pas trouvée";
        }
        return $modaliteReponseId;
    }

    /**
     * Récupère une modalité de réponse avec son format de réponse associé pour un userId donné
     */
    public function create(array $data)
    {
        
        return ModaliteReponse::create($data);
    }

    /**
     * Met à jour une modalité de réponse en fonction de son id
     */
    public function update($id, array $data)
    {
        $modaliteReponse = ModaliteReponse::findOrFail($id);
        $modaliteReponse->update($data);
        return $modaliteReponse;
    }

    /**
     * Supprime une modalité de réponse en fonction de son id
     */
    public function delete($id)
    {
        return ModaliteReponse::destroy($id);
    }
}

?>