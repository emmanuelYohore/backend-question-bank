<?php 
namespace App\Repositories\Eloquent;

use App\Models\BankItem;
use App\Repositories\Interfaces\BankItemRepositoryInterface;

class BankItemRepository implements BankItemRepositoryInterface
{
    public function getAll()
    {
        return BankItem::all();
    }

    public function getById($id)
    {
        $bankItemId = BankItem::findOrFail($id);
        if (empty($bankItemId)) {
            return "bank Item pas trouvé";
        }
        return $bankItemId;
    }

    public function create(array $data)
    {
        
        return BankItem::create($data);
    }

    public function update($id, array $data)
    {
        $bankItem = BankItem::findOrFail($id);
        $bankItem->update($data);
        return $bankItem;
    }

    public function delete($id)
    {
        return BankItem::destroy($id);
    }
}

?>