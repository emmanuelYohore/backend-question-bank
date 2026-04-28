<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttachBankItemsToEnqueteRequest extends FormRequest
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
        'bank_item_ids' => 'required|array|min:1',
        'bank_item_ids.*' => 'uuid|exists:bank_items,id',
    ];
    }
}
