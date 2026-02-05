<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Repositories\Interfaces\ItemRepositoryInterface;
use Tymon\JWTAuth\Facades\JWTAuth;

class ItemController extends Controller
{
    protected $itemRepository;

    public function __construct(
        ItemRepositoryInterface $itemRepository
    ) {
         $this->itemRepository = $itemRepository;
    }

    public function index()
    {
        return response()->json($this->itemRepository->getAll());
    }

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


    public function show(string $id)
    {
         return response()->json($this->itemRepository->getById($id));
    }

    
    public function update(UpdateItemRequest $request, string $id)
    {   
        $data = $request->validated();       
        $item = $this->itemRepository->update($id, $data);
        
        return response()->json([
            "message" => "item Updated.",
            "item" => $item
        ], 200);
    }

   
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
