<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRepondantRequest extends FormRequest
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
            'session_id'      => 'required|string|max:255',
            'enquete_id'      => 'required|exists:enquetes,id',
            'ip_address'      => 'nullable|string|max:45',
            'user_agent'      => 'nullable|string|max:500',
            'started_at'      => 'nullable|date',
            'completed_at'    => 'nullable|date|after_or_equal:started_at'
        ];
    }
}
