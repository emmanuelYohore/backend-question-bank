<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttachItemsToBankRequest;
use App\Http\Requests\StoreBankItemRequest;
use App\Http\Requests\UpdateBankItemRequest;
use App\Models\BankItem;
use App\Repositories\Interfaces\BankItemRepositoryInterface;
use Illuminate\Http\Request;
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
         return response()->json($this->bankItemRepository->getById($id));
    }

    public function getOneBankItemForUserId(string $userId, string $bankItemId)
    {
        return response()->json($this->bankItemRepository->getOneBankItemForUserId($userId, $bankItemId));

    }

    public function getAllBankItemForUserId(string $userId, Request $request)
    {
        $query = BankItem::where('user_id', $userId);
        
        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }
        
        return response()->json($query->get());
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

    public function attachItems(AttachItemsToBankRequest $request, string $userId, string $bankItemId)
    {
        $bank = BankItem::findOrFail($bankItemId);

        if ($bank->user_id !== (int)$userId) {
            return response()->json([
                'error' => 'Vous n\'êtes pas autorisé à ajouter des items à cette bank'
            ], 403);
        }

        $authenticatedUser = JWTAuth::parseToken()->authenticate();
        if ($authenticatedUser->id !== (int)$userId) {
            return response()->json([
                'error' => 'Vous ne pouvez ajouter des items qu\'à vos propres banks'
            ], 403);
        }

        $data = $request->validated();
        
        $bank->items()->syncWithoutDetaching($data['item_ids']);
        
        $attachedItems = $bank->items()->whereIn('items.id', $data['item_ids'])->get();
        
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

        if ($bank->user_id !== (int)$userId) {
            return response()->json([
                'error' => 'Vous n\'êtes pas autorisé à retirer des items de cette bank'
            ], 403);
        }

        $authenticatedUser = JWTAuth::parseToken()->authenticate();
        if ($authenticatedUser->id !== (int)$userId) {
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
