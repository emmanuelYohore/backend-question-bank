<?php

namespace App\Http\Requests;

use App\Support\HtmlSanitizer;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEnqueteRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $payload = [];

        if ($this->has('description')) {
            $payload['description'] = HtmlSanitizer::sanitizeEnqueteHtml((string) $this->input('description', ''));
        }

        if ($this->has('start_message')) {
            $payload['start_message'] = HtmlSanitizer::sanitizeEnqueteHtml((string) $this->input('start_message', ''));
        }

        if ($this->has('end_message')) {
            $payload['end_message'] = HtmlSanitizer::sanitizeEnqueteHtml((string) $this->input('end_message', ''));
        }

        if ($payload !== []) {
            $this->merge($payload);
        }
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
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:800',
            'start_message' => 'sometimes|string|max:800',
            'end_message' => 'sometimes|string|max:800',
            'archiver' => 'sometimes|boolean',
            'url_enquete' => 'sometimes|string|url|max:255'
        ];
    }
}
