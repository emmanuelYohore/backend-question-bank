<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBankItemItemRequest;
use App\Http\Requests\UpdateBankItemItemRequest;
use App\Repositories\Interfaces\BankItemItemRepositoryInterface;

class BankItemItemController extends Controller
{
    protected $bankItemItemRepository;
    public function __construct(
        BankItemItemRepositoryInterface $bankItemItemRepository
    ) {
         $this->bankItemItemRepository = $bankItemItemRepository;
    }

    public function index()
    {
        return response()->json($this->bankItemItemRepository->getAll());
    }

    public function store(StoreBankItemItemRequest $request)
    {
        $data = $request->validated();
        
        $bankItemItem = $this->bankItemItemRepository->create($data);
        return response()->json([
            "message"=> "bank item item crée avec succès",
            "bankItemItem"=> $bankItemItem
            ], 201);
    }


    public function show(string $id)
    {
         return response()->json($this->bankItemItemRepository->getById($id));
    }

    public function update(UpdateBankItemItemRequest $request, string $id)
    {   
        $data = $request->validated();       
        $bankItemItem = $this->bankItemItemRepository->update($id, $data);
        
        return response()->json([
            "message" => "bank item item Updated.",
            "bankItemItem" => $bankItemItem
        ], 200);
    }

   
    public function destroy(string $id)
    {
        try {
           $this->bankItemItemRepository->delete($id);
            return response()->json([
            'bank item item deleted'
            ]);
        } catch (\Throwable $th) {
             return response()->json([
            'error deleted' 
            ]);
        }
        
    }
}
