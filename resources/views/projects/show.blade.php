@extends('layouts.app')

@section('content')
<!-- Hero Section with gradient -->
<section class="relative min-h-[40vh] flex items-center justify-center overflow-hidden gradient-mesh">
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>
    </div>
    
    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
        <!-- Back Button -->
        <div class="mb-8">
            <a href="/" class="inline-flex items-center glass-dark text-white px-6 py-3 rounded-full font-medium hover:scale-105 transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Tillbaka
            </a>
        </div>

        <!-- Project Header -->
        <header>
            <h1 class="text-5xl md:text-7xl font-bold text-white mb-6">{{ $project->title }}</h1>
            
            @if($project->summary)
                <p class="text-xl md:text-2xl text-white/90 leading-relaxed max-w-3xl mx-auto">{{ $project->summary }}</p>
            @endif
            
            <!-- Technologies -->
            @if($project->technologies && count($project->technologies) > 0)
                <div class="flex flex-wrap justify-center gap-3 mt-8">
                    @foreach($project->technologies as $tech)
                        <span class="px-4 py-2 bg-white/20 backdrop-blur-sm text-white font-semibold rounded-full border border-white/30">
                            {{ $tech }}
                        </span>
                    @endforeach
                </div>
            @endif
        </header>
    </div>
</section>

<!-- Main Content -->
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 -mt-20 relative z-10">

    <!-- Screenshot/Cover Image -->
    @if($project->screenshot_path || $project->cover_image)
        <div class="mb-12 rounded-3xl overflow-hidden shadow-2xl bg-white">
            @if($project->screenshot_path)
                <img src="{{ asset('storage/' . $project->screenshot_path) }}" alt="{{ $project->title }}" loading="lazy" class="w-full">
            @elseif($project->cover_image)
                <img src="{{ asset('storage/' . $project->cover_image) }}" alt="{{ $project->title }}" loading="lazy" class="w-full">
            @endif
        </div>
    @endif

    <!-- Project Links -->
    @if($project->live_url || $project->repo_url)
        <div class="flex flex-wrap gap-4 mb-12 justify-center">
            @if($project->live_url)
                <a href="{{ $project->live_url }}" target="_blank" rel="noopener" class="group inline-flex items-center bg-gradient-to-r from-purple-600 to-pink-600 text-white px-8 py-4 rounded-full font-semibold hover:scale-105 transition-all shadow-lg glow-hover">
                    <svg class="w-5 h-5 mr-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                    Besök live-sajt
                </a>
            @endif
            
            @if($project->repo_url)
                <a href="{{ $project->repo_url }}" target="_blank" rel="noopener" class="inline-flex items-center glass border border-purple-200 text-gray-700 px-8 py-4 rounded-full font-semibold hover:scale-105 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.865 8.17 6.839 9.49.5.092.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.603-3.369-1.34-3.369-1.34-.454-1.156-1.11-1.463-1.11-1.463-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.831.092-.646.35-1.086.636-1.336-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.578 9.578 0 0112 6.836c.85.004 1.705.114 2.504.336 1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.203 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.743 0 .267.18.578.688.48C19.138 20.167 22 16.418 22 12c0-5.523-4.477-10-10-10z"/>
                    </svg>
                    Se källkod
                </a>
            @endif
        </div>
    @endif

    <!-- Project Description -->
    @if($project->description)
        <div class="glass rounded-3xl p-8 md:p-12 mb-12">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">
                <span class="gradient-text">Om projektet</span>
            </h2>
            <div class="text-gray-700 text-lg leading-relaxed whitespace-pre-line">
                {{ $project->description }}
            </div>
        </div>
    @endif

    <!-- Gallery -->
    @if($project->gallery && count($project->gallery) > 0)
        <div class="mb-12">
            <h2 class="text-3xl md:text-4xl font-bold mb-8">
                <span class="gradient-text">Galleri</span>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($project->gallery as $image)
                    <div class="rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all hover:scale-105 bg-white">
                        <img src="{{ asset('storage/' . $image) }}" alt="{{ $project->title }}" loading="lazy" class="w-full h-auto">
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Project Meta -->
    <div class="glass rounded-3xl p-8 md:p-12 mb-12 border border-purple-100">
        <h3 class="text-2xl md:text-3xl font-bold mb-6">
            <span class="gradient-text">Projektinformation</span>
        </h3>
        <dl class="space-y-3">
            <div class="flex justify-between py-2 border-b border-gray-200">
                <dt class="font-semibold text-gray-700">Status:</dt>
                <dd class="text-gray-600">
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                        {{ $project->status->value === 'published' ? 'Publicerad' : 'Utkast' }}
                    </span>
                </dd>
            </div>
            
            @if($project->featured)
                <div class="flex justify-between py-2 border-b border-gray-200">
                    <dt class="font-semibold text-gray-700">Featured:</dt>
                    <dd class="text-gray-600">
                        <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm font-medium">
                            Utvalt projekt
                        </span>
                    </dd>
                </div>
            @endif
            
            <div class="flex justify-between py-2">
                <dt class="font-semibold text-gray-700">Skapad:</dt>
                <dd class="text-gray-600">{{ $project->created_at->format('j F Y') }}</dd>
            </div>
        </dl>
    </div>

    <!-- CTA Section -->
    <div class="relative bg-gradient-to-br from-purple-600 via-pink-600 to-blue-600 rounded-3xl p-12 md:p-16 text-center text-white overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full filter blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-white rounded-full filter blur-3xl"></div>
        </div>
        
        <div class="relative">
            <h2 class="text-3xl md:text-5xl font-bold mb-4">Intresserad av ett liknande projekt?</h2>
            <p class="text-xl md:text-2xl text-white/90 mb-8 max-w-2xl mx-auto">Låt oss diskutera hur jag kan hjälpa dig!</p>
            <a href="/#contact" class="group inline-flex items-center bg-white text-purple-600 px-8 py-4 rounded-full font-semibold text-lg hover:scale-105 transition-all shadow-lg gap-2">
                <span>Kontakta mig</span>
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
    </div>
</div>
@endsection
