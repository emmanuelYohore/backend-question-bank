<?php 
namespace App\Repositories\Eloquent;

use App\Models\Enquete;
use App\Models\User;
use App\Repositories\Interfaces\EnqueteRepositoryInterface;

class EnqueteRepository implements EnqueteRepositoryInterface
{
    public function getAll()
    {
        return Enquete::all();
    }

    public function getById($id)
    {
        return Enquete::findOrFail($id);
    }

    public function getOneEnqueteForUserId($userId, $enqueteId)
    {
        $enquete = Enquete::where('user_id', $userId)
            ->where('id', $enqueteId)
            ->with(['bankItems' => function ($query) {
                $query->withPivot('id', 'mode', 'ordre', 'nombre_items_aleatoires')
                      ->orderBy('AQUALI_enquete_banks.ordre')
                      ->with(['items' => function ($q) {
                          $q->with(['formatReponse', 'modaliteReponses' => function ($mq) {
                              $mq->with('formatReponse')->orderBy('ordre');
                          }]);
                      }]);
            }])
            ->firstOrFail();

        $enquete->bankItems->transform(function ($bankItem) {
            $bankItem->enquete_bank_id = $bankItem->pivot->id;
            $bankItem->mode = $bankItem->pivot->mode;
            $bankItem->nombre_items_aleatoires = $bankItem->pivot->nombre_items_aleatoires;
            return $bankItem;
        });

        return $enquete;
    }

    public function getAllEnqueteForUserId($userId)
    {
        $enquetes = User::findOrFail($userId)
            ->enquetes()
            ->with(['bankItems' => function ($query) {
                $query->withPivot('id', 'mode', 'ordre', 'nombre_items_aleatoires')
                      ->orderBy('AQUALI_enquete_banks.ordre')
                      ->with(['items' => function ($q) {
                          $q->with(['formatReponse', 'modaliteReponses' => function ($mq) {
                              $mq->with('formatReponse')->orderBy('ordre');
                          }]);
                      }]);
            }])
            ->get();

        $enquetes->each(function ($enquete) {
            $enquete->bankItems->transform(function ($bankItem) {
                $bankItem->enquete_bank_id = $bankItem->pivot->id;
                $bankItem->mode = $bankItem->pivot->mode;
                $bankItem->nombre_items_aleatoires = $bankItem->pivot->nombre_items_aleatoires;
                return $bankItem;
            });
        });

        return $enquetes;
    }

    public function create(array $data)
    {
        return Enquete::create($data);
    }

    public function update($id, array $data)
    {
        $enquete = Enquete::findOrFail($id);
        $enquete->update($data);
        return $enquete;
    }

    public function delete($id)
    {
        return Enquete::destroy($id);
    }
}