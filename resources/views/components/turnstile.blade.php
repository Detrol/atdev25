@props(['theme' => 'light', 'size' => 'normal'])

@if(config('turnstile.enabled'))
{{-- Cloudflare Turnstile Widget --}}
<div
    class="cf-turnstile"
    data-sitekey="{{ config('turnstile.site_key') }}"
    data-theme="{{ $theme }}"
    data-size="{{ $size }}"
    {{ $attributes }}
></div>
@else
{{-- Development mode: Bypass Turnstile with hidden field --}}
<input type="hidden" name="cf-turnstile-response" value="dev-bypass">
@endif
