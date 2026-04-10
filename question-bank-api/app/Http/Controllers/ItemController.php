<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Models\Item;
use App\Repositories\Interfaces\ItemRepositoryInterface;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class ItemController extends Controller
{
    protected $itemRepository;

    public function __construct(
        ItemRepositoryInterface $itemRepository
    ) {
         $this->itemRepository = $itemRepository;
    }

    /**
     * Récupère tous les items et recherche par question
     */
    public function index(Request $request)
    {
        $query = Item::query()->with('formatReponse', 'modaliteReponses');
        if ($search = $request->input('search')) {
            $query->where('question', 'like', "%{$search}%");
        }
        return response()->json($query->get());
    }

    /**
     * Crée un nouvel item
     */
    public function store(StoreItemRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = JWTAuth::parseToken()->authenticate()->id;

        $item = $this->itemRepository->create($data);
        return response()->json([
            "message"=> "item crée avec succès",
            "item"=> $item
            ], 201);
    }

    /**
     * Récupère un item en fonction de son id
     */
    public function show(string $id)
    {
         return response()->json($this->itemRepository->getById($id));
    }

    /**
     * Récupère un item avec son format de réponse et ses modalités associés pour un userId donné
     */
    public function getOneItemForUserId(string $userId, string $itemId)
    {
        return response()->json($this->itemRepository->getOneItemForUserId($userId, $itemId));

    }

    /**
     * Récupère tous les items avec leur format de réponse et leurs modalités associés pour un userId donné
     */
    public function getAllItemForUserId(string $userId, Request $request)
    {
        $query = Item::where('user_id', $userId)->with('formatReponse', 'modaliteReponses');
        
        if ($search = $request->input('search')) {
            $query->where('question', 'like', "%{$search}%");
        }
        
        return response()->json($query->get());
    }
    
    /**
     * Met à jour un item en fonction de son id
     */
    public function update(UpdateItemRequest $request, string $id)
    {   
        $data = $request->validated();       
        $item = $this->itemRepository->update($id, $data);
        
        return response()->json([
            "message" => "item Updated.",
            "item" => $item
        ], 200);
    }

   /**
    * Supprime un item en fonction de son id
    */
    public function destroy(string $id)
    {
        try {
           $this->itemRepository->delete($id);
            return response()->json([
            'item deleted'
            ]);
        } catch (\Throwable $th) {
             return response()->json([
            'error deleted' 
            ]);
        }
        
    }
}
