<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
class StoreReponseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'repondant_id' => 'required|exists:AQUALI_repondants,id',
            'enquete_id' => 'required|exists:AQUALI_enquetes,id',
            'item_id' => 'required|exists:AQUALI_items,id',
            'modalite_reponse_id' => 'nullable|exists:AQUALI_modalite_reponses,id',
            'valeur_texte' => 'nullable|string',
            'valeur_evn' => 'nullable|string',
        ];
    }

    
}