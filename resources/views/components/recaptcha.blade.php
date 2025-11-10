@props(['action' => 'submit'])

@if(config('recaptcha.enabled'))
{{-- reCAPTCHA v3 hidden input (token added via JavaScript on form submit) --}}
<input type="hidden" name="g-recaptcha-response" class="g-recaptcha-response" {{ $attributes }}>
@else
{{-- Development mode: Bypass reCAPTCHA with hidden field --}}
<input type="hidden" name="g-recaptcha-response" value="dev-bypass">
@endif
