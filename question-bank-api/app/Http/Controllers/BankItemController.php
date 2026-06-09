<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttachItemsToBankRequest;
use App\Http\Requests\StoreBankItemRequest;
use App\Http\Requests\UpdateBankItemRequest;
use App\Models\BankItem;
use App\Repositories\Interfaces\BankItemRepositoryInterface;
use BankItemException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;


class BankItemController extends Controller
{
    protected $bankItemRepository;

    public function __construct(
        BankItemRepositoryInterface $bankItemRepository
    ) {
         $this->bankItemRepository = $bankItemRepository;
    }

    public function index(Request $request)
    {
        $query = BankItem::query();
        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }
        return response()->json($query->get());
        
    }

    
    public function store(StoreBankItemRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = JWTAuth::parseToken()->authenticate()->id;
        
        $bankItem = $this->bankItemRepository->create($data);
        return response()->json([
            "message"=> "bankItem crée avec succès",
            "bankItem"=> $bankItem
            ], 201);
    }

    
    public function show(string $id)
    {
        try {
            return response()->json($this->bankItemRepository->getById($id));
        } catch (BankItemException $e ) {
            return response()->json([
                'error' => $e->notBankItemIdMessage()
            ], 404);
        }
    }

    
    public function getOneBankItemForUserId(string $userId, string $bankItemId)
    {
        return response()->json($this->bankItemRepository->getOneBankItemForUserId($userId, $bankItemId));

    }

    
    public function getAllBankItemForUserId(string $userId, Request $request)
    {
        $query = BankItem::where('user_id', $userId)->with(['items' => function ($q) {
            $q->with(['formatReponse', 'modaliteReponses' => function ($mq) {
                $mq->with('formatReponse');
            }]);
        }]);
        
        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }
        
        return response()->json($query->get(), 200);
    }
       
   
    public function update(UpdateBankItemRequest $request, string $id)
    {   
        $data = $request->validated();       
        $bankItem = $this->bankItemRepository->update($id, $data);
        
        return response()->json([
            "message" => "bankItem Updated.",
            "bankItem" => $bankItem
        ], 200);
    }

    
    public function saveItemsOrder(Request $request, string $userId, string $bankItemId)
    {
        $data = $request->validate([
            'ordered_item_ids' => 'required|array',
            'ordered_item_ids.*' => 'uuid|exists:AQUALI_items,id',
        ]);

        $orderedItemIds = $data['ordered_item_ids'];

        $bankItem = BankItem::where('user_id', $userId)
                            ->where('id', $bankItemId)
                            ->firstOrFail();

        $attachedItemIds = $bankItem->items()->pluck('AQUALI_items.id')->toArray();

        $syncData = [];
        foreach ($orderedItemIds as $index => $itemId) {
            $syncData[$itemId] = ['ordre' => $index + 1];
            if (!in_array($itemId, $attachedItemIds, true)) {
                $syncData[$itemId]['id'] = (string) Str::uuid();
            }
        }

        $bankItem->items()->syncWithoutDetaching($syncData);

        return response()->json([
            'message' => 'Order of items saved successfully.',
            'bank_id' => $bankItem->id,
            'user_id' => $userId,
            'ordered_item_ids' => $orderedItemIds
        ], 200);

    }

    public function attachItems(AttachItemsToBankRequest $request, string $userId, string $bankItemId)
    {
        $bank = BankItem::findOrFail($bankItemId);

        if ($bank->user_id !== $userId) {
            return response()->json([
                'error' => 'Vous n\'êtes pas autorisé à ajouter des items à cette bank'
            ], 403);
        }

        $authenticatedUser = JWTAuth::parseToken()->authenticate();
        if ($authenticatedUser->id !== $userId) {
            return response()->json([
                'error' => 'Vous ne pouvez ajouter des items qu\'à vos propres banks'
            ], 403);
        }

        $data = $request->validated();

        //Si l'item est archivée, on ne peut pas l'ajouter à la banque

            if (BankItem::whereHas('items', function ($query) use ($data) {
                $query->whereIn('AQUALI_items.id', $data['item_ids']);
                $query->where('AQUALI_items.archived', true);
            })->exists()) {
                return response()->json([
                    'error' => 'Vous ne pouvez pas ajouter d\'items archivés à cette bank'
                ], 400);
            }

        $existingItemIds = $bank->items()->pluck('AQUALI_items.id')->toArray();
        $newItemIds = collect($data['item_ids'])->diff($existingItemIds)->values()->all();

        $attachData = [];
        foreach ($newItemIds as $itemId) {
            $attachData[$itemId] = ['id' => (string) Str::uuid()];
        }

        if (!empty($attachData)) {
            $bank->items()->attach($attachData);
        }

        $attachedItems = $bank->items()->whereIn('AQUALI_items.id', $newItemIds ?: $data['item_ids'])->get();
        
        return response()->json([
            'message' => 'Items ajoutés à la bank avec succès',
            'bank_id' => $bank->id,
            'user_id' => $userId,
            'attached_items' => $attachedItems
        ], 200);
    }

    public function detachItems(AttachItemsToBankRequest $request, string $userId, string $bankItemId)
    {
        $bank = BankItem::findOrFail($bankItemId);

        if ($bank->user_id !== $userId) {
            return response()->json([
                'error' => 'Vous n\'êtes pas autorisé à retirer des items de cette bank'
            ], 403);
        }

        $authenticatedUser = JWTAuth::parseToken()->authenticate();
        if ($authenticatedUser->id !== $userId) {
            return response()->json([
                'error' => 'Vous ne pouvez retirer des items qu\'à vos propres banks'
            ], 403);
        }

        $data = $request->validated();
        
        $bank->items()->detach($data['item_ids']);
        
        return response()->json([
            'message' => 'Items retirés de la bank avec succès',
            'bank_id' => $bank->id,
            'user_id' => $userId,
            'detached_item_ids' => $data['item_ids']
        ], 200);
    }
   
    /**
     * Supprime une bank item en fonction de son id
     */
    public function destroy(string $id)
    {
        try {
           $this->bankItemRepository->delete($id);
            return response()->json([
            'bankItem deleted'
            ]);
        } catch (\Throwable $th) {
             return response()->json([
            'error deleted' 
            ]);
        }
        
    }
}
