<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
            'website' => ['nullable', 'max:0'], // Honeypot field
            'price_estimation_id' => ['nullable', 'integer', 'exists:price_estimations,id'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Vänligen ange ditt namn.',
            'email.required' => 'Vänligen ange din e-postadress.',
            'email.email' => 'Vänligen ange en giltig e-postadress.',
            'message.required' => 'Vänligen skriv ett meddelande.',
            'message.min' => 'Meddelandet måste vara minst 10 tecken.',
            'message.max' => 'Meddelandet får inte vara längre än 5000 tecken.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check honeypot
            if ($this->filled('website')) {
                abort(422, 'Spam detected.');
            }
        });
    }
}
