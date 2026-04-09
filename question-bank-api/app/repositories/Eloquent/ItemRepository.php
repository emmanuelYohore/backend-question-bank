<?php 
namespace App\Repositories\Eloquent;

use App\Models\Item;
use App\Repositories\Interfaces\ItemRepositoryInterface;

class ItemRepository implements ItemRepositoryInterface
{
    public function getAll()
    {
        return Item::with(['formatReponse', 'modaliteReponses.formatReponse'])->get();
    }

    public function getById($id)
    {
        $itemId = Item::with(['formatReponse', 'modaliteReponses.formatReponse'])->findOrFail($id);
        if (empty($itemId)) {
            return "Item pas trouvé";
        }
        return $itemId;
    }

    //recupère un item avec son format de réponse et ses modalités associés pour un userId
    public function getOneItemForUserId($userId, $ItemId)
    {
        return Item::with(['formatReponse', 'modaliteReponses.formatReponse'])
                    ->where('user_id', $userId)
                    ->where('id', $ItemId)
                    ->firstOrFail();
    }
    
    //recupère tous les items avec leur format de réponse et leurs modalités associés pour un userId donné
    public function getAllItemForUserId($id)
    {
        return Item::with(['formatReponse', 'modaliteReponses.formatReponse'])
                ->where('user_id', $id)
                ->get();
    }

    public function create(array $data)
    {
        $item = Item::create($data);
        return $item->load(['formatReponse', 'modaliteReponses.formatReponse']);
    }

    public function update($id, array $data)
    {
        $item = Item::findOrFail($id);
        $item->update($data);
        return $item->load(['formatReponse', 'modaliteReponses.formatReponse']);
    }

    public function delete($id)
    {
        return Item::destroy($id);
    }
}

?>