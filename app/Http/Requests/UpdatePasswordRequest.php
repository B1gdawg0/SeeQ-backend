<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'new_password' => 'required|string|min:6|regex:/[A-Z]/|regex:/[a-z]/|regex:/[0-9]/',
        ];
    }

    public function messages(): array
    {
        return [
            'new_password.required' => 'The password field is required.',
            'new_password.string' => 'The password must be a string.',
            'new_password.min' => 'The password must be at least 6 characters.',
            'new_password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, and one number.',
        ];
    }
}
