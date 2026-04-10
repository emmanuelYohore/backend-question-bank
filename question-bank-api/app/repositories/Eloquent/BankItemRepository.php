<?php 
namespace App\Repositories\Eloquent;

use App\Models\BankItem;
use App\Repositories\Interfaces\BankItemRepositoryInterface;

class BankItemRepository implements BankItemRepositoryInterface
{
    /**
     * Récupère tous les bank items
     */
    public function getAll()
    {
        return BankItem::all();
    }

    /**
     * Récupère un bank item en fonction de son id
     */
    public function getById($id)
    {
        $bankItemId = BankItem::findOrFail($id);
        if (empty($bankItemId)) {
            return "bank Item pas trouvé";
        }
        return $bankItemId;
    }

    /**
     * Récupère un bank item avec ses items associés pour un userId donné
     */
    public function getOneBankItemForUserId($userId, $bankItemId)
    {
        return BankItem::where('user_id', $userId)
                      ->where('id', $bankItemId)
                      ->with(['items' => function ($query) {
                            $query->with('formatReponse', 'modaliteReponses.formatReponse');
                        }])
                      ->firstOrFail();
                      
    }

    /**
     * Récupère tous les bank items avec leurs items associés pour un userId donné
     */
    public function getAllBankItemForUserId($userId)
    {
        return BankItem::where('user_id', $userId)
                ->with(['items' => function ($query) {
                    $query->with('formatReponse', 'modaliteReponses.formatReponse');
                }])
                ->get();
                
    }

    /**
     * Crée un nouveau bank item
     */
    public function create(array $data)
    {
        
        return BankItem::create($data);
    }

    /**
     * Met à jour un bank item en fonction de son id
     */
    public function update($id, array $data)
    {
        $bankItem = BankItem::findOrFail($id);
        $bankItem->update($data);
        return $bankItem;
    }

    /**
     * Supprime un bank item en fonction de son id
     */
    public function delete($id)
    {
        return BankItem::destroy($id);
    }
}

?>