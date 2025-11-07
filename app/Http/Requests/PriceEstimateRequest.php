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
     * Prepare data for validation - add https:// if missing
     */
    protected function prepareForValidation(): void
    {
        if ($this->filled('website_url')) {
            $url = $this->website_url;

            // Add https:// if no protocol specified
            if (!preg_match('/^https?:\/\//', $url)) {
                $this->merge([
                    'website_url' => 'https://' . $url,
                ]);
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'service_category' => 'required|string|in:web_development,mobile_app,bug_fixes,performance,api_integration,security,maintenance,modernization',
            'description' => 'required|string|min:20|max:2000',
            'website_url' => [
                'nullable',
                'url',
                function ($attribute, $value, $fail) {
                    if (empty($value)) {
                        return;
                    }

                    // SSRF Protection: Block private/internal IPs
                    $host = parse_url($value, PHP_URL_HOST);

                    if (!$host) {
                        $fail('Ogiltig URL-format.');
                        return;
                    }

                    // Resolve IP address
                    $ip = gethostbyname($host);

                    // Block private/internal IPs
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
                        $fail('URL:en pekar på en privat eller intern adress.');
                        return;
                    }

                    // Block localhost
                    $blockedHosts = ['localhost', '127.0.0.1', '0.0.0.0', '::1'];
                    if (in_array(strtolower($host), $blockedHosts)) {
                        $fail('URL:en får inte peka på localhost.');
                    }
                },
            ],
        ];
    }

    /**
     * Get custom error messages (Swedish).
     */
    public function messages(): array
    {
        return [
            'service_category.required' => 'Vänligen välj en tjänstekategori.',
            'service_category.in' => 'Ogiltig tjänstekategori vald.',
            'description.required' => 'Vänligen beskriv ditt projekt.',
            'description.min' => 'Projektbeskrivningen måste vara minst :min tecken.',
            'description.max' => 'Projektbeskrivningen får inte överstiga :max tecken.',
            'website_url.url' => 'Vänligen ange en giltig URL.',
            'website_url.regex' => 'URL:en måste börja med http:// eller https://',
        ];
    }
}
