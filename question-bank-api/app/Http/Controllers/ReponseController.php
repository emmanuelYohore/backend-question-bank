<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReponseRequest;
use App\Http\Requests\UpdateReponseRequest;
use App\Repositories\Interfaces\ReponseRepositoryInterface;
class ReponseController extends Controller
{
    protected $reponseRepository;

    public function __construct(
        ReponseRepositoryInterface $reponseRepository
    ) {
         $this->reponseRepository = $reponseRepository;
    }

    public function index()
    {
        return response()->json($this->reponseRepository->getAll());
    }

    public function store(StoreReponseRequest $request)
    {
        $data = $request->validated();
        try {

        $reponse = $this->reponseRepository->create($data);
        return response()->json([
            "message"=> "reponse crée avec succès",
            "reponse"=> $reponse
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to create reponse, please try again',
                'debug' => $e->getMessage(), 
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ], 500);
        }
    }


    public function show(string $id)
    {
         return response()->json($this->reponseRepository->getById($id));
    }

    public function update(UpdateReponseRequest $request, string $id)
    {   
        $data = $request->validated();       
        $reponse = $this->reponseRepository->update($id, $data);
        
        return response()->json([
            "message" => "reponse Updated.",
            "reponse" => $reponse
        ], 200);
    }

    public function destroy(string $id)
    {
        try {
           $this->reponseRepository->delete($id);
            return response()->json([
            'reponse deleted'
            ]);
        } catch (\Throwable $th) {
             return response()->json([
            'error deleted' 
            ]);
        }
        
    }
}
