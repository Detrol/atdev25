<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PriceEstimateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Public endpoint
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'description' => 'required|string|min:20|max:2000',
        ];
    }

    /**
     * Get custom error messages (Swedish).
     */
    public function messages(): array
    {
        return [
            'description.required' => 'Vänligen beskriv ditt projekt.',
            'description.min' => 'Projektbeskrivningen måste vara minst :min tecken.',
            'description.max' => 'Projektbeskrivningen får inte överstiga :max tecken.',
        ];
    }
}
