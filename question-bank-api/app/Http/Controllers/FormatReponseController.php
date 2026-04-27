<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFormatReponseRequest;
use App\Http\Requests\UpdateFormatReponseRequest;
use App\Repositories\Interfaces\FormatReponseRepositoryInterface;

class FormatReponseController extends Controller
{
    protected $formatReponseRepository;

    public function __construct(
        FormatReponseRepositoryInterface $formatReponseRepository
    ) {
         $this->formatReponseRepository = $formatReponseRepository;
    }

    /**
     * Récupère tous les formatReponses
      *
     */
    public function index()
    {
        return response()->json($this->formatReponseRepository->getAll());
    }

    /**
     * Crée une formatReponse
     */
    public function store(StoreFormatReponseRequest $request)
    {
        $data = $request->validated();
        // if ( $data['type'] == 'texte') {
        //    $data['nb_min_select'] = 1;
        //    $data['nb_max_select'] = 1;
        // }
        
        $formatReponse = $this->formatReponseRepository->create($data);
        return response()->json([
            "message"=> "formatReponse crée avec succès",
            "formatReponse"=> $formatReponse
            ], 201);
    }

    /**
     * Récupère une formatReponse en fonction de son id
     */
    public function show(string $id)
    {
         return response()->json($this->formatReponseRepository->getById($id));
    }

    /**
     * Met à jour une formatReponse en fonction de son id
     */
    public function update(UpdateFormatReponseRequest $request, string $id)
    {   
        $data = $request->validated();       
        $formatReponse = $this->formatReponseRepository->update($id, $data);
        
        return response()->json([
            "message" => "formatReponse Updated.",
            "formatReponse" => $formatReponse
        ], 200);
    }

   /**
    * Supprime un formatReponse en fonction de son id
    */
    public function destroy(string $id)
    {
        try {
           $this->formatReponseRepository->delete($id);
            return response()->json([
            'formatReponse deleted'
            ]);
        } catch (\Throwable $th) {
             return response()->json([
            'error deleted' 
            ]);
        }
        
    }
}
