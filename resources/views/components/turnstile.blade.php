@props(['theme' => 'light'])

@if(config('turnstile.enabled'))
{{-- Cloudflare Turnstile Widget (Invisible) --}}
<div
    class="cf-turnstile"
    data-sitekey="{{ config('turnstile.site_key') }}"
    data-theme="{{ $theme }}"
    data-size="invisible"
    {{ $attributes }}
></div>
@else
{{-- Development mode: Bypass Turnstile with hidden field --}}
<input type="hidden" name="cf-turnstile-response" value="dev-bypass">
@endif

@once
@push('scripts')
@if(config('turnstile.enabled'))
{{-- Load Turnstile API script --}}
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
@endif
@endpush
@endonce
