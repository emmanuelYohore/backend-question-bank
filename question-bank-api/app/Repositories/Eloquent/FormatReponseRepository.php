<?php 
namespace App\Repositories\Eloquent;

use App\Models\FormatReponse;
use App\Repositories\Interfaces\FormatReponseRepositoryInterface;

class FormatReponseRepository implements FormatReponseRepositoryInterface
{
    /**
     * Récupère tous les formats de réponse
     */
    public function getAll()
    {
        return FormatReponse::all();
    }

    /**
     * Récupère un format de réponse en fonction de son id
     */
    public function getById($id)
    {
        $formatReponseId = FormatReponse::findOrFail($id);
        
        return $formatReponseId;
    }

    /**
     * Crée un nouveau format de réponse
     */
    public function create(array $data)
    {
        
        return FormatReponse::create($data);
    }

    /**
     * Met à jour un format de réponse en fonction de son id
     */
    public function update($id, array $data)
    {
        $formatReponse = FormatReponse::findOrFail($id);
        $formatReponse->update($data);
        return $formatReponse;
    }

    /**
     * Supprime un format de réponse en fonction de son id
     */
    public function delete($id)
    {
        return FormatReponse::destroy($id);
    }
}

?>