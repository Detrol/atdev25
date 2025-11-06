<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FaqRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Admin middleware hanterar auktorisering
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string'],
            'tags' => ['nullable', 'string'], // Kommer att konverteras till array
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'active' => ['nullable', 'boolean'],
            'show_in_ai_chat' => ['nullable', 'boolean'],
            'show_in_price_calculator' => ['nullable', 'boolean'],
            'show_in_public_faq' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Prepare data for validation by converting tags string to array.
     */
    protected function prepareForValidation(): void
    {
        if ($this->filled('tags') && is_string($this->tags)) {
            // Konvertera kommaseparerad sträng till array
            $tags = array_map('trim', explode(',', $this->tags));
            $tags = array_filter($tags); // Ta bort tomma element
            $this->merge(['tags' => $tags]);
        }

        // Sätt default-värden för checkboxes om de inte är ifyllda
        if (!$this->has('active')) {
            $this->merge(['active' => false]);
        }
        if (!$this->has('show_in_ai_chat')) {
            $this->merge(['show_in_ai_chat' => false]);
        }
        if (!$this->has('show_in_price_calculator')) {
            $this->merge(['show_in_price_calculator' => false]);
        }
        if (!$this->has('show_in_public_faq')) {
            $this->merge(['show_in_public_faq' => false]);
        }
    }

    /**
     * Get custom validation messages in Swedish.
     */
    public function messages(): array
    {
        return [
            'question.required' => 'Frågan är obligatorisk.',
            'question.max' => 'Frågan får max vara 255 tecken.',
            'answer.required' => 'Svaret är obligatoriskt.',
            'sort_order.integer' => 'Sorteringsordning måste vara ett heltal.',
            'sort_order.min' => 'Sorteringsordning kan inte vara negativ.',
        ];
    }
}
