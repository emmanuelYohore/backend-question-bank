<?php

namespace App\Http\Controllers;


use App\Http\Requests\StoreRepondantRequest;
use App\Http\Requests\UpdateRepondantRequest;
use App\Repositories\Interfaces\RepondantRepositoryInterface;

class RepondantController extends Controller
{
    protected $repondantRepository;

    public function __construct(
        RepondantRepositoryInterface $repondantRepository
    ) {
         $this->repondantRepository = $repondantRepository;
    }

    /**
     * Récupère tous les repondants
      *
     */
    public function index()
    {
        return response()->json($this->repondantRepository->getAll());
    }

    public function store(StoreRepondantRequest $request)
    {
        $data = $request->validated();
        
        $repondant = $this->repondantRepository->create($data);
        return response()->json([
            "message"=> "repondant crée avec succès",
            "repondant"=> $repondant
            ], 201);
    }


    public function show(string $id)
    {
         return response()->json($this->repondantRepository->getById($id));
    }

    public function update(UpdateRepondantRequest $request, string $id)
    {   
        $data = $request->validated();       
        $repondant = $this->repondantRepository->update($id, $data);
        
        return response()->json([
            "message" => "repondant Updated.",
            "repondant" => $repondant
        ], 200);
    }

   
    public function destroy(string $id)
    {
        try {
           $this->repondantRepository->delete($id);
            return response()->json([
            'repondant deleted'
            ]);
        } catch (\Throwable $th) {
             return response()->json([
            'error deleted' 
            ]);
        }
        
    }
}
