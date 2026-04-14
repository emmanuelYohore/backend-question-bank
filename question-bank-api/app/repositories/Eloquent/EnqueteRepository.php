<?php 
namespace App\Repositories\Eloquent;

use App\Models\Enquete;
use App\Models\User;
use App\Repositories\Interfaces\EnqueteRepositoryInterface;


class EnqueteRepository implements EnqueteRepositoryInterface
{
    /**
     * Récupère toutes les enquêtes
     */
    public function getAll()
    {
        return Enquete::all();
    }

    /**
     * Récupère une enquête en fonction de son id
     */
    public function getById($id)
    {
        $enqueteId = Enquete::findOrFail($id);
        if (empty($enqueteId)) {
            return "enquête pas trouvé";
        }
        return $enqueteId;
    }

    /**
     * Récupère une enquête avec ses bank items associés pour un userId
     */
    public function getOneEnqueteForUserId($userId, $enqueteId)
    {
        return Enquete::where('user_id', $userId)
                      ->where('id', $enqueteId)
                      ->with(['bankItems' => function ($query) {
                          $query->withPivot('ordre')
                                ->orderBy('enquete_banks.ordre')
                                ->with(['items' => function ($q) {
                                    $q->with(['formatReponse', 'modaliteReponses' => function ($mq) {
                                        $mq->with('formatReponse');
                                    }]);
                                }]);
                      }])
                      ->firstOrFail();
    }

    /**
     * Récupère toutes les enquêtes avec leurs bank items associés pour un userId donné ordonnés par ordre défini dans la table de pivot
     */
    public function getAllEnqueteForUserId($userId)
    {
        return User::findOrFail($userId)
                ->enquetes()
                ->with(['bankItems' => function ($query) {
                    $query->withPivot('ordre')
                          ->orderBy('enquete_banks.ordre')
                          ->with(['items' => function ($q) {
                              $q->with(['formatReponse', 'modaliteReponses' => function ($mq) {
                                  $mq->with('formatReponse');
                              }]);
                          }]);
                }])
                ->get();
    }

    /**
     * Crée une nouvelle enquête
     */
    public function create(array $data)
    {
        return Enquete::create($data);
    }

    /**
     * Met à jour une enquête en fonction de son id
     */
    public function update($id, array $data)
    {
        $enquete = Enquete::findOrFail($id);
        $enquete->update($data);
        return $enquete;
    }

    /**
     * Supprime une enquête en fonction de son id
     */
    public function delete($id)
    {
        return Enquete::destroy($id);
    }
}

?>