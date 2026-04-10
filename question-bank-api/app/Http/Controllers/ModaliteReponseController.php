<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreModaliteReponseRequest;
use App\Http\Requests\UpdateModaliteReponseRequest;
use App\Repositories\Interfaces\ModaliteReponseRepositoryInterface;


class ModaliteReponseController extends Controller
{
    protected $modaliteReponseRepository;

    public function __construct(
        ModaliteReponseRepositoryInterface $modaliteReponseRepository
    ) {
         $this->modaliteReponseRepository = $modaliteReponseRepository;
    }

    /**
     * Récupère tous les modaliteReponses
     */
    public function index()
    {
        return response()->json($this->modaliteReponseRepository->getAll());
    }

    /**
     * Crée une nouvelle modaliteReponse
     */
    public function store(StoreModaliteReponseRequest $request)
    {
        $data = $request->validated();
        
        $modaliteReponse = $this->modaliteReponseRepository->create($data);
        return response()->json([
            "message"=> "modaliteReponse crée avec succès",
            "modaliteReponse"=> $modaliteReponse
            ], 201);
    }

    /**
     * Récupère une modaliteReponse en fonction de son id
     */
    public function show(string $id)
    {
         return response()->json($this->modaliteReponseRepository->getById($id));
    }

    /**
     * Met à jour une modaliteReponse en fonction de son id
     */
    public function update(UpdateModaliteReponseRequest $request, string $id)
    {   
        $data = $request->validated();       
        $modaliteReponse = $this->modaliteReponseRepository->update($id, $data);
        
        return response()->json([
            "message" => "modaliteReponse Updated.",
            "modaliteReponse" => $modaliteReponse
        ], 200);
    }

    /**
     * Supprime une modaliteReponse en fonction de son id
     */
    public function destroy(string $id)
    {
        try {
           $this->modaliteReponseRepository->delete($id);
            return response()->json([
            'modaliteReponse deleted'
            ]);
        } catch (\Throwable $th) {
             return response()->json([
            'error deleted' 
            ]);
        }
        
    }
}
