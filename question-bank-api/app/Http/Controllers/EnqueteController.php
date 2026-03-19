<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttachBankItemsToEnqueteRequest;
use App\Http\Requests\StoreEnqueteRequest;
use App\Http\Requests\UpdateEnqueteRequest;
use App\Models\Enquete;
use App\Repositories\Interfaces\EnqueteRepositoryInterface;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
class EnqueteController extends Controller
{
    protected $enqueteRepository;

    public function __construct(
        EnqueteRepositoryInterface $enqueteRepository
    ) {
         $this->enqueteRepository = $enqueteRepository;
    }

    public function index()
    {
        return response()->json($this->enqueteRepository->getAll());
    }

    public function store(StoreEnqueteRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = JWTAuth::parseToken()->authenticate()->id;
        $data['url_enquete'] = url('/enquete/' . Str::random(32));

        $enquete = $this->enqueteRepository->create($data);
        return response()->json([
            "message"=> "enquete crée avec succès",
            "enquete"=> $enquete
            ], 201);
    }


    public function show(string $id)
    {
         return response()->json($this->enqueteRepository->getById($id));
    }

    public function getOneEnqueteForUserId(string $userId, string $enqueteId)
    {
        return response()->json($this->enqueteRepository->getOneEnqueteForUserId($userId, $enqueteId));

    }


    public function getAllEnqueteForUserId(string $userId)
    {
        return response()->json($this->enqueteRepository->getAllEnqueteForUserId($userId));
    }

    public function attachBankItems(AttachBankItemsToEnqueteRequest $request, string $userId, string $enqueteId)
    {
        $enquete = Enquete::findOrFail($enqueteId);

        if ($enquete->user_id !== (int)$userId) {
            return response()->json([
                'error' => 'Vous n\'êtes pas autorisé à ajouter des banques à cette enquête'
            ], 403);
        }

        $authenticatedUser = JWTAuth::parseToken()->authenticate();
        if ($authenticatedUser->id !== (int)$userId) {
            return response()->json([
                'error' => 'Vous ne pouvez ajouter des banques d\'items qu\'à vos propres enquêtes'
            ], 403);
        }

        $data = $request->validated();
        
        $enquete->bankItems()->syncWithoutDetaching($data['bank_item_ids']);
        
        $attachedBankItems = $enquete->bankItems()->whereIn('bank_items.id', $data['bank_item_ids'])->get();
        
        return response()->json([
            'message' => 'Banque d\'items ajoutés à l\'enquête avec succès',
            'enquete_id' => $enquete->id,
            'user_id' => $userId,
            'attached_bank_items' => $attachedBankItems
        ], 200);
    }

    public function detachBankItems(AttachBankItemsToEnqueteRequest $request, string $userId, string $enqueteId)
    {
        $enquete = Enquete::findOrFail($enqueteId);

        if ($enquete->user_id !== (int)$userId) {
            return response()->json([
                'error' => 'Vous n\'êtes pas autorisé à retirer des banques de cette enquête'
            ], 403);
        }

        $authenticatedUser = JWTAuth::parseToken()->authenticate();
        if ($authenticatedUser->id !== (int)$userId) {
            return response()->json([
                'error' => 'Vous ne pouvez retirer des banques d\'items qu\'à vos propres enquêtes'
            ], 403);
        }

        $data = $request->validated();
        
        $enquete->bankItems()->detach($data['bank_item_ids']);
        
        return response()->json([
            'message' => 'Banque d\'items retirés de l\'enquête avec succès',
            'enquete_id' => $enquete->id,
            'user_id' => $userId,
            'detached_bank_item_ids' => $data['bank_item_ids']
        ], 200);
    }

    

    
    public function update(UpdateEnqueteRequest $request, string $id)
    {   
        $data = $request->validated();
   
        $enquete = $this->enqueteRepository->update($id, $data);
        
        return response()->json([
            "message" => "enquete Updated.",
            "enquete" => $enquete
        ], 200);
    }

   
    public function destroy(string $id)
    {
        $this->enqueteRepository->delete($id);
        return response()->json([
            'enquete deleted' 
            ]);
    }
}
