<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBankItemRequest;
use App\Http\Requests\UpdateBankItemRequest;
use App\Repositories\Interfaces\BankItemRepositoryInterface;
use Tymon\JWTAuth\Facades\JWTAuth;

class BankItemController extends Controller
{
    protected $bankItemRepository;

    public function __construct(
        BankItemRepositoryInterface $bankItemRepository
    ) {
         $this->bankItemRepository = $bankItemRepository;
    }

    public function index()
    {
        return response()->json($this->bankItemRepository->getAll());
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

    
    public function update(UpdateBankItemRequest $request, string $id)
    {   
        $data = $request->validated();       
        $bankItem = $this->bankItemRepository->update($id, $data);
        
        return response()->json([
            "message" => "bankItem Updated.",
            "bankItem" => $bankItem
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
