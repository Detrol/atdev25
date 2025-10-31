<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $serviceId = $this->route('service')?->id;

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('services', 'slug')->ignore($serviceId),
            ],
            'description' => ['required', 'string', 'max:1000'],
            'icon' => ['required', 'string', 'in:code,puzzle-piece,wrench,rocket'],
            'features' => ['nullable', 'array'],
            'features.*' => ['string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'title' => 'titel',
            'slug' => 'slug',
            'description' => 'beskrivning',
            'icon' => 'ikon',
            'features' => 'funktioner',
            'sort_order' => 'sorteringsordning',
            'is_active' => 'aktiv',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Titel är obligatorisk.',
            'description.required' => 'Beskrivning är obligatorisk.',
            'icon.required' => 'Ikon är obligatorisk.',
            'icon.in' => 'Ogiltig ikon vald.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert features from comma-separated string to array if needed
        if ($this->has('features') && is_string($this->features)) {
            $features = array_filter(
                array_map('trim', explode(',', $this->features)),
                fn($value) => !empty($value)
            );
            $this->merge(['features' => $features]);
        }

        // Set is_active default
        if (!$this->has('is_active')) {
            $this->merge(['is_active' => false]);
        }

        // Set default sort_order if not provided
        if (!$this->has('sort_order') || $this->sort_order === null) {
            $this->merge(['sort_order' => 0]);
        }
    }
}
