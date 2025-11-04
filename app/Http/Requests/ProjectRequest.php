<?php

namespace App\Http\Requests;

use App\Enums\ProjectStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert comma-separated technologies string to array
        if ($this->has('technologies') && is_string($this->technologies)) {
            $this->merge([
                'technologies' => array_filter(
                    array_map('trim', explode(',', $this->technologies)),
                    fn($value) => !empty($value)
                ),
            ]);
        }

        // Convert featured checkbox to boolean
        if (!$this->has('featured')) {
            $this->merge(['featured' => false]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $projectId = $this->route('project')?->id;

        return [
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('projects')->ignore($projectId)],
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['required', 'string', 'max:500'],
            'description' => ['required', 'string'],
            'cover_image' => ['nullable', 'string', 'max:500'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['string', 'max:500'],
            'live_url' => ['nullable', 'url', 'max:500'],
            'repo_url' => ['nullable', 'url', 'max:500'],
            'technologies' => ['nullable', 'array'],
            'technologies.*' => ['string', 'max:100'],
            'status' => ['required', Rule::enum(ProjectStatus::class)],
            'featured' => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
        ];
    }
}
