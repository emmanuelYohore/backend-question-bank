<?php 
namespace App\Repositories\Eloquent;

use App\Models\Item;
use App\Repositories\Interfaces\ItemRepositoryInterface;

class ItemRepository implements ItemRepositoryInterface
{   
    
    private function withRelations()
    {
        return [
            'formatReponse',
            'modaliteReponses' => fn($q) => $q->orderBy('ordre'),
            'modaliteReponses.formatReponse',
        ];
    }

    public function getAll()
    {
        return Item::with($this->withRelations())->get();
    }

    public function getById($id)
    {
        return Item::with($this->withRelations())->findOrFail($id);
    }

    public function getOneItemForUserId($userId, $itemId)
    {
        return Item::with($this->withRelations())
            ->where('user_id', $userId)
            ->where('id', $itemId)
            ->firstOrFail();
    }

    public function getAllItemForUserId($id)
    {
        return Item::with($this->withRelations())
            ->where('user_id', $id)
            ->get();
    }

    public function create(array $data)
    {
        $item = Item::create($data);
        return $item;
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