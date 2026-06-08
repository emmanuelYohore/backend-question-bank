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

    public function index(Request $request)
    {
        $query = Enquete::query();
        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }
        return response()->json($query->get());
    }

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

    public function show(string $id)
    {
         return response()->json($this->enqueteRepository->getById($id));
    }

    //si l'enquete est archivée on retourne l'enquete est archéivée, sinon on retourne l'enquete avec les items mélangés si le mode de la banque est aléatoire
    public function getByUrl(string $url)
{
    $enquete = Enquete::where('url_enquete', 'like', '%' . $url)
        ->with([
            'bankItems' => function ($query) {
                $query->withPivot('id', 'mode', 'ordre', 'nombre_items_aleatoires')
                      ->orderBy('enquete_banks.ordre');
            },
            'bankItems.items.formatReponse',
            'bankItems.items.modaliteReponses',
        ])
        ->firstOrFail();

    if ($enquete->archived) {
        return response()->json([
            'message' => "Cette enquête est archivée et n'est plus accessible"
        ], 410);
    }

    foreach ($enquete->bankItems as $bankItem) {
        $mode = $bankItem->pivot->mode; // ← lecture depuis le pivot uniquement
        if ($mode === 'aleatoire') {
            $bankItem->setRelation('items', $bankItem->items->shuffle());
        }
    }

    return response()->json($enquete);
}

    public function getOneEnqueteForUserId(string $userId, string $enqueteId)
    {
        return response()->json($this->enqueteRepository->getOneEnqueteForUserId($userId, $enqueteId));

    }

    public function getAllEnqueteForUserId(string $userId, Request $request)
    {
        $query = Enquete::where('user_id', $userId);
        
        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }
        
        return response()->json($query->get());
    }

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
            'bank_item_ids.*' => 'string|uuid|exists:bank_items,id',
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

    public function detachBankItems(Request $request, string $userId, string $enqueteId)
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
            'bank_item_ids.*' => 'string|uuid|exists:bank_items,id',
        ]);

        $enquete->bankItems()->detach($data['bank_item_ids']);

        return response()->json([
            'message' => 'Banques d\'items supprimées de l\'enquête avec succès',
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

    // public function getReponsesByEnquete(string $enqueteId)
    // {
    //     try {
    //         $reponses =Reponse::where('enquete_id', $enqueteId)
    //             ->with(['item', 'modaliteReponse', 'repondant'])
    //             ->get()
    //             ->map(function ($reponse) {
    //                 return [
    //                     'id' => $reponse->id,
    //                     'enquete_id' => $reponse->enquete_id,
    //                     'enquete_title' => $reponse->enquete?->title,
    //                     'repondant' => $reponse->repondant_session_id,
    //                     //'item_id' => $reponse->item_id,
    //                     'item_question' => $reponse->item?->question,
    //                     //'modalite_reponse_id' => $reponse->modalite_reponse_id,
    //                     'format_reponse_type' => $reponse->item?->formatReponse?->type,
    //                     'valeur_modalite_reponse' => $reponse->modaliteReponse?->intitule,
    //                     'valeur_texte' => $reponse->valeur_texte,
    //                     'valeur_evn' => $reponse->valeur_evn,
    //                     'created_at' => $reponse->created_at,
    //                 ];
    //             });

    //         return response()->json($reponses, 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Erreur lors de la récupération des réponses',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    /**
     * Export survey responses to CSV format
     * CSV contains: respondent_id, datetime, item_code1, item_code2, ...
     */
    public function exportResponsesToCsv(string $enqueteId)
    {
        try {
            $enquete = Enquete::findOrFail($enqueteId);

            // Check authorization
            $authenticatedUser = JWTAuth::parseToken()->authenticate();
            if ($enquete->user_id !== $authenticatedUser->id) {
                return response()->json([
                    'error' => 'Vous n\'êtes pas autorisé à exporter les réponses de cette enquête'
                ], 403);
            }

            // Get all items for this survey, maintaining order from bankItems
            $bankItems = $enquete->bankItems()
                ->with('items')
                ->orderByPivot('ordre')
                ->get();
            
            // Build ordered items collection
            $orderedItems = [];
            foreach ($bankItems as $bankItem) {
                foreach ($bankItem->items as $item) {
                    // Use item ID to avoid duplicates while maintaining order
                    if (!isset($orderedItems[$item->id])) {
                        $orderedItems[$item->id] = $item;
                    }
                }
            }

            // Build a mapping of modaliteReponse ID to code for each item
            // This ensures codes are sequential per item (1, 2, 3...)
            $modaliteCodeMap = [];
            foreach ($orderedItems as $item) {
                $modalites = $item->modaliteReponses()->orderBy('ordre')->get();
                $code = 1;
                foreach ($modalites as $modalite) {
                    $modaliteCodeMap[$modalite->id] = $code;
                    $code++;
                }
            }

            // Get all respondents with their responses
            $repondants = $enquete->repondants()
                ->with(['reponses' => function ($query) use ($enqueteId) {
                    $query->where('enquete_id', $enqueteId)
                          ->with('modaliteReponse');
                }])
                ->get();

            // Build CSV header
            $header = ['id_rep', 'dateheure'];
            foreach ($orderedItems as $item) {
                $header[] = $item->nom_court;
            }

            // Build CSV data
            $rows = [];
            foreach ($repondants as $repondant) {
                $row = [
                    $repondant->id,
                    $repondant->started_at ? $repondant->started_at->format('Y/m/d H:i') : '',
                ];

                // Add responses for each item
                foreach ($orderedItems as $item) {
                    $response = $repondant->reponses->firstWhere('item_id', $item->id);
                    
                    if ($response) {
                        if ($response->modaliteReponse) {
                            // Use the mapped code for this modalité
                            $code = $modaliteCodeMap[$response->modaliteReponse->id] ?? '';
                        } else if ($response->valeur_texte) {
                            $code = $response->valeur_texte;
                        } else if ($response->valeur_evn) {
                            $code = $response->valeur_evn;
                        } else {
                            $code = '';
                        }
                        $row[] = $code;
                    } else {
                        $row[] = '';
                    }
                }

                $rows[] = $row;
            }

            // Generate CSV content
            $csvContent = $this->generateCsvContent([$header, ...$rows]);

            // Return CSV file download
            return response($csvContent, 200)
                ->header('Content-Type', 'text/csv; charset=utf-8')
                ->header('Content-Disposition', 'attachment; filename="reponses_enquete_' . $enquete->id . '.csv"');

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de l\'export des réponses',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export variable details to CSV format
     * CSV contains: item_code, item_title, modality_code, modality_title
     */
    public function exportVariableDetailsToCsv(string $enqueteId)
    {
        try {
            $enquete = Enquete::findOrFail($enqueteId);

            // Check authorization
            $authenticatedUser = JWTAuth::parseToken()->authenticate();
            if ($enquete->user_id !== $authenticatedUser->id) {
                return response()->json([
                    'error' => 'Vous n\'êtes pas autorisé à exporter les détails des variables de cette enquête'
                ], 403);
            }

            // Get all items for this survey, maintaining order from bankItems
            $bankItems = $enquete->bankItems()
                ->with('items.modaliteReponses')
                ->orderByPivot('ordre')
                ->get();
            
            // Build ordered items collection
            $orderedItems = [];
            foreach ($bankItems as $bankItem) {
                foreach ($bankItem->items as $item) {
                    // Use item ID to avoid duplicates while maintaining order
                    if (!isset($orderedItems[$item->id])) {
                        $orderedItems[$item->id] = $item;
                    }
                }
            }

            // Build CSV header
            $header = ['code_item', 'intitulé_item', 'code_modalité', 'intitulé_modalité'];

            // Build CSV data
            $rows = [];
            foreach ($orderedItems as $item) {
                $modalites = $item->modaliteReponses()->orderBy('ordre')->get();

                // Use a counter for each item to generate sequential codes starting from 1 next 2 etc ... if type of modality is qcm, qcu else code is vide 
                $code = 1;
                foreach ($modalites as $modalite) {
                    $rows[] = [
                        $item->nom_court,
                        $item->question,
                        $modalite->intitule ? $code : '',
                        $modalite->intitule,
                    ];
                    if ($modalite->intitule) {
                        $code++;
                    }
                }
            }

            // Generate CSV content
            $csvContent = $this->generateCsvContent([$header, ...$rows]);

            // Return CSV file download
            return response($csvContent, 200)
                ->header('Content-Type', 'text/csv; charset=utf-8')
                ->header('Content-Disposition', 'attachment; filename="variables_enquete_' . $enquete->id . '.csv"');

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de l\'export des détails des variables',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper function to generate CSV content
     */
    private function generateCsvContent(array $rows): string
    {
        $output = fopen('php://memory', 'w');
        
        // Set UTF-8 BOM for proper encoding in Excel
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        foreach ($rows as $row) {
            fputcsv($output, $row, ',', '"');
        }

        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);

        return $csvContent;
    }
}
