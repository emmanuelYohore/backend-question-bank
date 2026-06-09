<?php 
namespace App\Repositories\Eloquent;

use App\Models\BankItem;
use App\Models\User;
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
                        $query->orderBy('AQUALI_bank_item_items.ordre')
                            ->with('formatReponse', 'modaliteReponses.formatReponse');
                    }])
                    ->firstOrFail();
                      
    }

 
      /**
     * Récupère toutes les bank items  avec leurs bank items associés pour un userId donné ordonnés par ordre défini dans la table de pivot
     */
    public function getAllBankItemForUserId($userId)
    {
        return User::findOrFail($userId)
                ->bankItems()
                ->with(['items' => function ($query) {
                    $query->orderBy('AQUALI_bank_item_items.ordre')
                          ->with(['formatReponse', 'modaliteReponses.formatReponse']);
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