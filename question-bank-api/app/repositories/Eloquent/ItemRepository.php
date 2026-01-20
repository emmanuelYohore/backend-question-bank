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