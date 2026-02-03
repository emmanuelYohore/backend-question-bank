<?php 
namespace App\Repositories\Eloquent;

use App\Models\BankItemItem;
use App\Repositories\Interfaces\BankItemItemRepositoryInterface;

class BankItemItemRepository implements BankItemItemRepositoryInterface
{
    public function getAll()
    {
        return BankItemItem::all();
    }

    public function getById($id)
    {
        $BankItemItemId = BankItemItem::findOrFail($id);
        if (empty($BankItemItemId)) {
            return "BankItemItem pas trouvé";
        }
        return $BankItemItemId;
    }

    public function create(array $data)
    {
        
        return BankItemItem::create($data);
    }

    public function update($id, array $data)
    {
        $BankItemItem = BankItemItem::findOrFail($id);
        $BankItemItem->update($data);
        return $BankItemItem;
    }

    public function delete($id)
    {
        return BankItemItem::destroy($id);
    }
}

?>