<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEnqueteRequest;
use App\Http\Requests\UpdateEnqueteRequest;
use App\Repositories\Interfaces\EnqueteRepositoryInterface;
use Illuminate\Support\Facades\Auth;
class EnqueteController extends Controller
{
    protected $enqueteRepository;

    public function __construct(
        EnqueteRepositoryInterface $enqueteRepository
    ) {
         $this->enqueteRepository = $enqueteRepository;
    }

    public function index()
    {
        return response()->json($this->enqueteRepository->getAll());
    }

    public function store(StoreEnqueteRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        
        $enquete = $this->enqueteRepository->create($data);
        return response()->json([
            "message"=> "enquete crée avec succès",
            "enquete"=> $enquete
            ], 201);
    }


    public function show(string $id)
    {
         return response()->json($this->enqueteRepository->getById($id));
    }

    public function getOneEnqueteForUserId(string $userId, string $enqueteId)
    {
        return response()->json($this->enqueteRepository->getOneEnqueteForUserId($userId, $enqueteId));

    }

    public function getAllEnqueteForUserId(string $userId)
    {
        return response()->json($this->enqueteRepository->getAllEnqueteForUserId($userId));
    }

    
    public function update(UpdateEnqueteRequest $request, string $id)
    {   
        $data = $request->validated();
   
        $enquete = $this->enqueteRepository->update($id, $data);
        
        return response()->json([
            "message" => "enquete Updated.",
            "enquete" => $enquete
        ], 200);
    }

   
    public function destroy(string $id)
    {
        $this->enqueteRepository->delete($id);
        return response()->json([
            'enquete deleted' 
            ]);
    }
}
