<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttachBankItemsToEnqueteRequest;
use App\Http\Requests\StoreEnqueteRequest;
use App\Http\Requests\UpdateEnqueteRequest;
use App\Models\Enquete;
use App\Repositories\Interfaces\EnqueteRepositoryInterface;
use Illuminate\Http\Request;
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

    //recupère toutes les enquêtes et recherche par titre d'enquête
    public function index(Request $request)
    {
        $query = Enquete::query();
        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }
        return response()->json($query->get());
    }

    //crée une enquête
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

    //recupère une enquête en fonction de son id
    public function show(string $id)
    {
         return response()->json($this->enqueteRepository->getById($id));
    }

    //recupère une enquête avec ses bank items associés pour un userId      
    public function getOneEnqueteForUserId(string $userId, string $enqueteId)
    {
        return response()->json($this->enqueteRepository->getOneEnqueteForUserId($userId, $enqueteId));

    }

    //recupère toutes les enquêtes avec leurs bank items associés pour un userId et recherche par titre d'enquête
    public function getAllEnqueteForUserId(string $userId, Request $request)
    {
        $query = Enquete::where('user_id', $userId);
        
        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }
        
        return response()->json($query->get());
    }

    //ajoute des bank items à une enquête pour un userId
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

        //si la banque est archivée, on ne peut pas l'ajouter à l'enquête
        if (Enquete::whereHas('bankItems', function ($query) use ($data) {
            $query->whereIn('bank_items.id', $data['bank_item_ids']);
            $query->where('bank_items.archived', true);
        })->exists()) {
            return response()->json([
                'error' => 'Vous ne pouvez pas ajouter de banques archivées à cette enquête'
            ], 400);
        }
        
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

    //met à jour une enquête en fonction de son id
    public function update(UpdateEnqueteRequest $request, string $id)
    {   
        $data = $request->validated();
   
        $enquete = $this->enqueteRepository->update($id, $data);
        
        return response()->json([
            "message" => "enquete Updated.",
            "enquete" => $enquete
        ], 200);
    }

   //supprime une enquête en fonction de son id
    public function destroy(string $id)
    {
        $this->enqueteRepository->delete($id);
        return response()->json([
            'enquete deleted' 
            ]);
    }
}
