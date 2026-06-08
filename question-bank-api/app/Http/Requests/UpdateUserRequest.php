<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('id');
        
        return [
            'name' => 'sometimes|string|min:3,max:50',
            'surname' => 'sometimes|string|min:3,max:50',
            'email' => 'sometimes|email|unique:AQUALI_users,email,' . $userId,
            'password' => 'sometimes|string|min:6',
            'role' => 'sometimes|string|in:user,admin'
        ];
    }
}
