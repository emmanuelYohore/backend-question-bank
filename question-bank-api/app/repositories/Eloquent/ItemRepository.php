<?php 
namespace App\Repositories\Eloquent;

use App\Models\Item;
use App\Repositories\Interfaces\ItemRepositoryInterface;

class ItemRepository implements ItemRepositoryInterface
{   
    /**
     * Récupère tous les items avec leur format de réponse et leurs modalités associés
     */
    public function getAll()
    {
        return Item::with(['formatReponse', 'modaliteReponses.formatReponse'])->get();
    }

    /**
     * Récupère un item en fonction de son id avec son format de réponse et ses modalités associés
     */
    public function getById($id)
    {
        $itemId = Item::with(['formatReponse', 'modaliteReponses.formatReponse'])->findOrFail($id);
        if (empty($itemId)) {
            return "Item pas trouvé";
        }
        return $itemId;
    }

    /**
     * Récupère un item avec son format de réponse et ses modalités associés pour un userId donné
     */
    public function getOneItemForUserId($userId, $ItemId)
    {
        return Item::with(['formatReponse', 'modaliteReponses.formatReponse'])
                    ->where('user_id', $userId)
                    ->where('id', $ItemId)
                    ->firstOrFail();
    }
    
    /**
     * Récupère tous les items avec leur format de réponse et leurs modalités associés pour un userId donné
     */
    public function getAllItemForUserId($id)
    {
        return Item::with(['formatReponse', 'modaliteReponses.formatReponse'])
                ->where('user_id', $id)
                ->get();
    }

    /**
     * Crée un nouvel item
     */
    public function create(array $data)
    {
        $item = Item::create($data);
        return $item->load(['formatReponse', 'modaliteReponses.formatReponse']);
    }

    /**
     * Met à jour un item en fonction de son id
     */
    public function update($id, array $data)
    {
        $item = Item::findOrFail($id);
        $item->update($data);
        return $item->load(['formatReponse', 'modaliteReponses.formatReponse']);
    }

    /**
     * Supprime un item en fonction de son id
     */
    public function delete($id)
    {
        return Item::destroy($id);
    }
}

?>