<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReponseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
             'repondant_id'  => 'required|exists:repondants,id',
             'item_id'  => 'required|exists:items,id',
             'modalite_reponse_id'  => 'required|exists:modalite_reponses,id',
             'valeur_texte'  => 'sometimes|string|max:255'
        ];
    }
}
