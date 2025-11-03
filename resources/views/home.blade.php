@extends('layouts.app')

@section('content')
@include('partials.navigation', ['currentPage' => 'home'])

{{-- Hero Section --}}
<x-home.hero :profile="$profile" :avatarMedia="$avatarMedia" />

{{-- About/Journey Section --}}
<x-home.about :profile="$profile" :workImageMedia="$workImageMedia" />

{{-- How I Work Section --}}
<x-home.how-i-work />

{{-- Timeline & Stats Section --}}
<x-home.timeline />

{{-- Services Section --}}
<x-home.services :services="$services" />

{{-- Projects Section --}}
<x-home.projects :projects="$projects" />

{{-- Interactive Demos CTA --}}
<x-home.demos-cta />

{{-- FAQ Section --}}
<x-home.faq />

{{-- Website Audit CTA --}}
<x-home.audit-cta />

{{-- Contact Section --}}
<x-home.contact />

@endsection
