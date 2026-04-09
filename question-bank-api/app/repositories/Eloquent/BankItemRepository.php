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

    //recupère un bank item avec ses items associés pour un userId donné
    public function getOneBankItemForUserId($userId, $bankItemId)
    {
        return BankItem::where('user_id', $userId)
                      ->where('id', $bankItemId)
                      ->with(['items' => function ($query) {
                            $query->with('formatReponse', 'modaliteReponses.formatReponse');
                        }])
                      ->firstOrFail();
                      
    }

    //recupère tous les bank items avec leurs items associés pour un userId donné
    public function getAllBankItemForUserId($userId)
    {
        return BankItem::where('user_id', $userId)
                ->with(['items' => function ($query) {
                    $query->with('formatReponse', 'modaliteReponses.formatReponse');
                }])
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