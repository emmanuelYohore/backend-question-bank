<?php 
namespace App\Repositories\Eloquent;

use App\Models\Item;
use App\Repositories\Interfaces\ItemRepositoryInterface;

class ItemRepository implements ItemRepositoryInterface
{
    public function getAll()
    {
        return Item::all();
    }

    public function getById($id)
    {
        $itemId = Item::findOrFail($id);
        if (empty($itemId)) {
            return "Item pas trouvé";
        }
        return $itemId;
    }


    public function getOneItemForUserId($userId, $ItemId)
    {
        return Item::with(['formatReponse', 'modaliteReponses'])
                    ->where('user_id', $userId)
                    ->where('id', $ItemId)
                    ->firstOrFail();
    }
    
    public function getAllItemForUserId($id)
    {
        return Item::with(['formatReponse', 'modaliteReponses'])
                ->where('user_id', $id)
                ->get();
    }

    public function create(array $data)
    {
        
        return Item::create($data);
    }

    public function update($id, array $data)
    {
        $item = Item::findOrFail($id);
        $item->update($data);
        return $item;
    }

    public function delete($id)
    {
        return Item::destroy($id);
    }
}

?>