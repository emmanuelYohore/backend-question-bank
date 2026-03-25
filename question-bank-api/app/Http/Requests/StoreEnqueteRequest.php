<?php

namespace App\Http\Requests;

use App\Support\HtmlSanitizer;
use Illuminate\Foundation\Http\FormRequest;

class StoreEnqueteRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'description' => HtmlSanitizer::sanitizeEnqueteHtml((string) $this->input('description', '')),
            'start_message' => HtmlSanitizer::sanitizeEnqueteHtml((string) $this->input('start_message', '')),
            'end_message' => HtmlSanitizer::sanitizeEnqueteHtml((string) $this->input('end_message', '')),
        ]);
    }

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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_message' => 'required|string',
            'end_message' => 'required|string',
            'archiver' => 'sometimes|boolean',
        ];
    }
}
