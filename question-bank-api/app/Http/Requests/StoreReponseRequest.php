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
            'repondant_id' => 'required|exists:repondants,id',
            'item_id' => 'required|exists:items,id',
            'modalite_reponse_id' => 'nullable|exists:modalite_reponses,id',
            'valeur_texte' => 'nullable|string',
            'valeur_evn' => 'nullable|string',
        ];
    }

    
}