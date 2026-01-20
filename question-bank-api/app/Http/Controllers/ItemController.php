<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBankItemRequest;
use App\Http\Requests\UpdateBankItemRequest;
use App\Repositories\Interfaces\ItemRepositoryInterface;

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

    public function store(StoreBankItemRequest $request)
    {
        $data = $request->validated();
        
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

    
    public function update(UpdateBankItemRequest $request, string $id)
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
