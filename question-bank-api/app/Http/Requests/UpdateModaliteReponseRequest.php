<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateModaliteReponseRequest extends FormRequest
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
             'format_reponse_id'  => 'sometimes|exists:format_reponses,id',
             'intitule' => 'sometimes|string|max:255',
             'valeur' => 'sometimes|integer',
        ];
    }
}
