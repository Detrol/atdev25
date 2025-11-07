<?php

namespace App\Http\Requests;

use App\Rules\ValidTurnstile;
use Illuminate\Foundation\Http\FormRequest;

class WebsiteAuditRequest extends FormRequest
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
            'url' => 'required|url|max:255',
            'name' => 'required|string|min:2|max:100',
            'email' => 'required|email|max:255',
            'company' => 'nullable|string|max:100',
            'website' => 'nullable|max:0', // Honeypot field
            'cf-turnstile-response' => ['required', new ValidTurnstile],
        ];
    }

    /**
     * Get custom validation messages in Swedish
     */
    public function messages(): array
    {
        return [
            'url.required' => 'Webbplats-URL är obligatoriskt.',
            'url.url' => 'Ange en giltig URL (börjar med http:// eller https://).',
            'url.max' => 'URL:en får max vara 255 tecken.',

            'name.required' => 'Ditt namn är obligatoriskt.',
            'name.min' => 'Namnet måste vara minst 2 tecken.',
            'name.max' => 'Namnet får max vara 100 tecken.',

            'email.required' => 'E-postadress är obligatoriskt.',
            'email.email' => 'Ange en giltig e-postadress.',
            'email.max' => 'E-postadressen får max vara 255 tecken.',

            'company.max' => 'Företagsnamnet får max vara 100 tecken.',

            'website.max' => 'Detta fält ska vara tomt.',

            'cf-turnstile-response.required' => 'Vänligen slutför säkerhetsverifieringen.',
        ];
    }
}
