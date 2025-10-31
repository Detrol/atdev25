<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatRequest extends FormRequest
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
            'message' => 'required|string|max:1000',
            'session_id' => 'required|string|uuid',
        ];
    }

    /**
     * Get custom messages for validation errors (in Swedish).
     */
    public function messages(): array
    {
        return [
            'message.required' => 'Meddelande krävs.',
            'message.string' => 'Meddelandet måste vara text.',
            'message.max' => 'Meddelandet får inte vara längre än 1000 tecken.',
            'session_id.required' => 'Session-ID krävs.',
            'session_id.string' => 'Session-ID måste vara text.',
            'session_id.uuid' => 'Session-ID måste vara ett giltigt UUID.',
        ];
    }
}
