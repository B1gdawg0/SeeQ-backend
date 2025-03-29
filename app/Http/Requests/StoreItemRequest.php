<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'api_url' => 'required|string',
            'api_key' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'api_url.required' => 'API URL is required.',
            'api_url.string' => 'API URL must be a string.',
            'api_key.required' => 'API key is required.',
            'api_key.string' => 'API key must be a string.',
        ];
    }
}
