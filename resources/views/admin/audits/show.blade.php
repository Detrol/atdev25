@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="mb-8">
        <a href="{{ route('admin.audits.index') }}" class="text-indigo-600 hover:text-indigo-800 mb-4 inline-block">&larr; Tillbaka till listan</a>
        <h1 class="text-3xl font-bold text-gray-800">Audit Detaljer</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Information</h3>
            <dl class="space-y-3">
                <div>
                    <dt class="text-sm text-gray-600">URL</dt>
                    <dd class="text-sm font-medium"><a href="{{ $audit->url }}" target="_blank" class="text-indigo-600 hover:underline">{{ $audit->url }}</a></dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-600">Namn</dt>
                    <dd class="text-sm font-medium">{{ $audit->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-600">E-post</dt>
                    <dd class="text-sm font-medium">{{ $audit->email }}</dd>
                </div>
                @if($audit->company)
                <div>
                    <dt class="text-sm text-gray-600">Företag</dt>
                    <dd class="text-sm font-medium">{{ $audit->company }}</dd>
                </div>
                @endif
                <div>
                    <dt class="text-sm text-gray-600">Skapad</dt>
                    <dd class="text-sm font-medium">{{ $audit->created_at->format('Y-m-d H:i') }}</dd>
                </div>
            </dl>
        </div>

        @if($audit->isCompleted())
        <div class="bg-green-50 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">SEO</h3>
            <div class="text-5xl font-bold text-green-700">{{ $audit->seo_score }}</div>
            <div class="text-sm text-gray-600 mt-2">Poäng</div>
        </div>

        <div class="bg-blue-50 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Performance</h3>
            <div class="text-5xl font-bold text-blue-700">{{ $audit->performance_score }}</div>
            <div class="text-sm text-gray-600 mt-2">Poäng</div>
        </div>
        @endif
    </div>

    @if($audit->screenshot_path)
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h3 class="text-lg font-semibold mb-4">Screenshot</h3>
        <img src="{{ asset('storage/' . $audit->screenshot_path) }}" alt="Screenshot" class="w-full rounded-lg border">
    </div>
    @endif

    @if($audit->isCompleted() && $audit->ai_report)
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">AI-Rapport</h3>
        <div class="prose max-w-none">
            {!! \Illuminate\Support\Str::markdown($audit->ai_report) !!}
        </div>
    </div>
    @endif

    @if($audit->status === 'failed')
    <div class="bg-red-50 rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-red-800 mb-2">Misslyckades</h3>
        <p class="text-red-700">Granskningen misslyckades. Kontrollera att URL:en är korrekt och tillgänglig.</p>
    </div>
    @endif
</div>
@endsection
