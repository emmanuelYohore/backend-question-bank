<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttachBankItemsToEnqueteRequest;
use App\Http\Requests\StoreEnqueteRequest;
use App\Http\Requests\UpdateEnqueteRequest;
use App\Models\Enquete;
use App\Models\Reponse;
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

    /**
     * Récupère toutes les enquêtes et recherche par titre d'enquête
     */
    public function index(Request $request)
    {
        $query = Enquete::query();
        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }
        return response()->json($query->get());
    }

    /**
     * Crée une nouvelle enquête
     */
    public function store(StoreEnqueteRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = JWTAuth::parseToken()->authenticate()->id;
        $data['url_enquete'] = env('FRONTEND_URL', 'http://localhost:5173') . '/survey/' . Str::random(32);

        $enquete = $this->enqueteRepository->create($data);     
        
        return response()->json([
            "message"=> "enquete crée avec succès",
            "enquete"=> $enquete
            ], 201);
    }

    /**
     * Récupère une enquête en fonction de son id
     */
    public function show(string $id)
    {
         return response()->json($this->enqueteRepository->getById($id));
    }

    /**
     * Récupère une enquête par son URL (accès anonyme)
     */
    public function getByUrl(string $url)
    {
        $enquete = Enquete::where('url_enquete', 'like', '%' . $url)
            ->with(['bankItems.items.formatReponse', 'bankItems.items.modaliteReponses'])
            ->firstOrFail();

        return response()->json($enquete);
    }

    /**
     * Récupère une enquête avec ses bank items associés pour un userId
     */
    public function getOneEnqueteForUserId(string $userId, string $enqueteId)
    {
        return response()->json($this->enqueteRepository->getOneEnqueteForUserId($userId, $enqueteId));

    }

    /**
     * Récupère toutes les enquêtes avec leurs bank items associés pour un userId et recherche par titre d'enquête
     */
    public function getAllEnqueteForUserId(string $userId, Request $request)
    {
        $query = Enquete::where('user_id', $userId);
        
        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }
        
        return response()->json($query->get());
    }

    /**
     * Ajoute des bank items à une enquête pour un userId
     */
    public function attachBankItems(AttachBankItemsToEnqueteRequest $request, string $userId, string $enqueteId)
    {
        $enquete = Enquete::findOrFail($enqueteId);

        if ($enquete->user_id !== $userId) {
            return response()->json([
                'error' => 'Vous n\'êtes pas autorisé à ajouter des banques à cette enquête'
            ], 403);
        }

        $authenticatedUser = JWTAuth::parseToken()->authenticate();
        if ($authenticatedUser->id !== $userId) {
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
        
$existingBankItemIds = $enquete->bankItems()->pluck('bank_items.id')->toArray();
        $newBankItemIds = collect($data['bank_item_ids'])->diff($existingBankItemIds)->values()->all();

        $attachData = [];
        foreach ($newBankItemIds as $bankItemId) {
            $attachData[$bankItemId] = ['id' => (string) Str::uuid()];
        }

        if (!empty($attachData)) {
            $enquete->bankItems()->attach($attachData);
        }        
        $attachedBankItems = $enquete->bankItems()->whereIn('bank_items.id', $data['bank_item_ids'])->get();
        
        return response()->json([
            'message' => 'Banque d\'items ajoutés à l\'enquête avec succès',
            'enquete_id' => $enquete->id,
            'user_id' => $userId,
            'attached_bank_items' => $attachedBankItems
        ], 200);
    }

    /**
     * Persists the order of bank items for an enquete
     */
    public function saveBankItemsOrder(Request $request, string $userId, string $enqueteId)
    {
        $enquete = Enquete::findOrFail($enqueteId);

        if ($enquete->user_id !== $userId) {
            return response()->json([
                'error' => 'Vous n\'êtes pas autorisé à modifier les banques de cette enquête'
            ], 403);
        }

        $authenticatedUser = JWTAuth::parseToken()->authenticate();
        if ($authenticatedUser->id !== $userId) {
            return response()->json([
                'error' => 'Vous ne pouvez modifier les banques d\'items qu\'à vos propres enquêtes'
            ], 403);
        }

        $data = $request->validate([
            'bank_item_ids' => 'required|array',
            'bank_item_ids.*' => 'integer|exists:bank_items,id',
        ]);

        foreach ($data['bank_item_ids'] as $index => $bankItemId) {
            $enqueteBank = $enquete->enqueteBanks()->where('bank_item_id', $bankItemId)->first();
            if ($enqueteBank) {
                $enqueteBank->ordre = $index;
                $enqueteBank->save();
            }
        }

        return response()->json([
            'message' => 'Ordre des banques d\'items mis à jour avec succès',
            'enquete_id' => $enquete->id,
            'user_id' => $userId,
            'ordered_bank_item_ids' => $data['bank_item_ids']
        ], 200);
    }

    /**
     * Met à jour une enquête en fonction de son id
     */
    public function update(UpdateEnqueteRequest $request, string $id)
    {   
        $data = $request->validated();
   
        $enquete = $this->enqueteRepository->update($id, $data);
        
        return response()->json([
            "message" => "enquete Updated.",
            "enquete" => $enquete
        ], 200);
    }

   
    /**
     * Supprime une enquête en fonction de son id
     */
    public function destroy(string $id)
    {
        $this->enqueteRepository->delete($id);
        return response()->json([
            'enquete deleted' 
            ]);
    }

    /**
     * Récupère toutes les réponses d'une enquête
     */
    public function getReponsesByEnquete(string $enqueteId)
    {
        try {
            $reponses =Reponse::where('enquete_id', $enqueteId)
                ->with(['item', 'modaliteReponse', 'repondant'])
                ->get()
                ->map(function ($reponse) {
                    return [
                        'id' => $reponse->id,
                        'enquete_id' => $reponse->enquete_id,
                        'enquete_title' => $reponse->enquete?->title,
                        'repondant' => $reponse->repondant_session_id,
                        'item_id' => $reponse->item_id,
                        'item_question' => $reponse->item?->question,
                        'modalite_reponse_id' => $reponse->modalite_reponse_id,
                        'format_reponse_type' => $reponse->item?->formatReponse?->type,
                        'modalite_reponse_intitule' => $reponse->modaliteReponse?->intitule,
                        'valeur_texte' => $reponse->valeur_texte,
                        'valeur_evn' => $reponse->valeur_evn,
                        'created_at' => $reponse->created_at,
                    ];
                });

            return response()->json($reponses, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la récupération des réponses',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
