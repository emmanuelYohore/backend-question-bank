<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEnqueteBankRequest;
use App\Http\Requests\UpdateEnqueteBankRequest;
use App\Repositories\Interfaces\EnqueteBankRepositoryInterface;


class EnqueteBankController extends Controller
{
    protected $enqueteBankRepository;

    public function __construct(
        EnqueteBankRepositoryInterface $enqueteBankRepository
    ) {
         $this->enqueteBankRepository = $enqueteBankRepository;
    }

    public function index()
    {
        return response()->json($this->enqueteBankRepository->getAll());
    }

    public function store(StoreEnqueteBankRequest $request)
    {
        $data = $request->validated();
        
        $enqueteBank = $this->enqueteBankRepository->create($data);
        return response()->json([
            "message"=> "enquete bank crée avec succès",
            "enqueteBank"=> $enqueteBank
            ], 201);
    }


    public function show(string $id)
    {
         return response()->json($this->enqueteBankRepository->getById($id));
    }

    public function update(UpdateEnqueteBankRequest $request, string $id)
    {   
        $data = $request->validated();       
        $enqueteBank = $this->enqueteBankRepository->update($id, $data);
        
        return response()->json([
            "message" => "enquete bank Updated.",
            "enqueteBank" => $enqueteBank
        ], 200);
    }

   
    public function destroy(string $id)
    {
        try {
           $this->enqueteBankRepository->delete($id);
            return response()->json([
            'enquete bank deleted'
            ]);
        } catch (\Throwable $th) {
             return response()->json([
            'error deleted' 
            ]);
        }
        
    }
}
