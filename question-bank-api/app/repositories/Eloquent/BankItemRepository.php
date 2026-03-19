<?php 
namespace App\Repositories\Eloquent;

use App\Models\BankItem;
use App\Models\User;
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

    public function getOneBankItemForUserId($userId, $bankItemId)
    {
        return BankItem::where('user_id', $userId)
                      ->where('id', $bankItemId)
                      ->with('items')
                      ->firstOrFail();
                      
    }

    public function getAllBankItemForUserId($userId)
    {
        return User::findOrFail($userId)
                ->bankItems()
                ->with('items')
                ->get();
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