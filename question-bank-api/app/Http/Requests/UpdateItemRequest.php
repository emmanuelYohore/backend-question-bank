<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
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
            'bank_item_id'      => 'sometimes|exists:bank_items,id',
            'format_reponse_id' => 'sometimes|exists:format_reponses,id',
            'question'          => 'sometimes|string|max:255',
            'ordre'             => 'sometimes|integer',
            'obligatoire'       => 'sometimes|boolean',
        ];
    }
}
