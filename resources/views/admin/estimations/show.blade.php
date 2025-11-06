@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold leading-6 text-gray-900">Prisestimering Detaljer</h1>
            <p class="mt-2 text-sm text-gray-700">Skapad {{ $estimation->created_at->format('Y-m-d H:i') }}</p>
        </div>
        <div class="mt-4 flex space-x-3 sm:mt-0">
            <a href="{{ route('admin.estimations.index') }}"
               class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                ← Tillbaka
            </a>
            <form action="{{ route('admin.estimations.destroy', $estimation) }}" method="POST"
                  onsubmit="return confirm('Är du säker på att du vill radera denna prisestimering?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500">
                    Radera
                </button>
            </form>
        </div>
    </div>

    {{-- Section 1: Project Analysis --}}
    <div class="overflow-hidden bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h2 class="text-lg font-semibold leading-6 text-gray-900">Projektanalys</h2>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                {{-- Service Category --}}
                <div>
                    <dt class="text-sm font-medium text-gray-500">Tjänstekategori</dt>
                    <dd class="mt-1">
                        @php
                            $categoryColors = [
                                'web_development' => 'bg-blue-100 text-blue-800',
                                'mobile_app' => 'bg-purple-100 text-purple-800',
                                'bug_fixes' => 'bg-red-100 text-red-800',
                                'performance' => 'bg-green-100 text-green-800',
                                'api_integration' => 'bg-yellow-100 text-yellow-800',
                                'security' => 'bg-orange-100 text-orange-800',
                                'maintenance' => 'bg-gray-100 text-gray-800',
                                'modernization' => 'bg-indigo-100 text-indigo-800',
                            ];
                            $categoryLabels = [
                                'web_development' => 'Webbutveckling',
                                'mobile_app' => 'Mobilapp',
                                'bug_fixes' => 'Buggfix',
                                'performance' => 'Prestanda',
                                'api_integration' => 'API-integration',
                                'security' => 'Säkerhet',
                                'maintenance' => 'Underhåll',
                                'modernization' => 'Modernisering',
                            ];
                            $colorClass = $categoryColors[$estimation->service_category] ?? 'bg-gray-100 text-gray-800';
                            $categoryLabel = $categoryLabels[$estimation->service_category] ?? $estimation->service_category;
                        @endphp
                        <span class="inline-flex items-center rounded-md px-2.5 py-0.5 text-sm font-medium {{ $colorClass }}">
                            {{ $categoryLabel }}
                        </span>
                    </dd>
                </div>

                {{-- Project Type --}}
                <div>
                    <dt class="text-sm font-medium text-gray-500">Projekttyp</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $estimation->project_type_label }}</dd>
                </div>

                {{-- Complexity --}}
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Komplexitet ({{ $estimation->complexity }}/10)</dt>
                    <dd class="mt-2">
                        <div class="flex items-center space-x-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $estimation->complexity * 10 }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ $estimation->complexity }}/10</span>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">{{ $estimation->complexity_label }}</p>
                    </dd>
                </div>

                {{-- Key Features --}}
                @if($estimation->key_features && count($estimation->key_features) > 0)
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Viktiga funktioner</dt>
                        <dd class="mt-2">
                            <ul class="space-y-1">
                                @foreach($estimation->key_features as $feature)
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-sm text-gray-700">{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </dd>
                    </div>
                @endif

                {{-- Description --}}
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Projektbeskrivning</dt>
                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $estimation->description }}</dd>
                </div>
            </dl>
        </div>
    </div>

    {{-- Section 2: Price Comparison --}}
    <div class="overflow-hidden bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h2 class="text-lg font-semibold leading-6 text-gray-900">Prisuppskattning</h2>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                {{-- Traditional Development --}}
                <div class="rounded-lg bg-gray-50 p-6 ring-1 ring-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">Traditionell Utveckling</h3>
                    <dl class="mt-4 space-y-3">
                        <div>
                            <dt class="text-sm text-gray-500">Timmar</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $estimation->hours_traditional }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Leveranstid</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $estimation->delivery_weeks_traditional }}</dd>
                        </div>
                        <div class="pt-3 border-t border-gray-300">
                            <dt class="text-sm text-gray-500">Pris (ex moms)</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $estimation->price_traditional }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Pris (inkl 25% moms)</dt>
                            <dd class="text-xl font-bold text-gray-900">{{ $estimation->price_traditional_vat }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- AI-Driven Development --}}
                <div class="rounded-lg bg-gradient-to-br from-indigo-50 to-purple-50 p-6 ring-1 ring-indigo-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-semibold text-gray-900">AI-Driven Utveckling</h3>
                        <span class="inline-flex items-center rounded-md bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                            {{ $estimation->savings_percent }}% besparing
                        </span>
                    </div>
                    <dl class="mt-4 space-y-3">
                        <div>
                            <dt class="text-sm text-gray-500">Timmar</dt>
                            <dd class="text-lg font-semibold text-indigo-900">{{ $estimation->hours_ai }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Leveranstid</dt>
                            <dd class="text-lg font-semibold text-indigo-900">{{ $estimation->delivery_weeks_ai }}</dd>
                        </div>
                        <div class="pt-3 border-t border-indigo-300">
                            <dt class="text-sm text-gray-500">Pris (ex moms)</dt>
                            <dd class="text-lg font-semibold text-indigo-900">{{ $estimation->price_ai }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Pris (inkl 25% moms)</dt>
                            <dd class="text-xl font-bold text-indigo-900">{{ $estimation->price_ai_vat }}</dd>
                        </div>
                        <div class="pt-3 border-t border-indigo-300">
                            <dt class="text-sm text-gray-500">Du sparar (inkl moms)</dt>
                            <dd class="text-xl font-bold text-green-600">{{ $estimation->savings_vat }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    {{-- Section 3: Metadata --}}
    <div class="overflow-hidden bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h2 class="text-lg font-semibold leading-6 text-gray-900">Metadata</h2>
        </div>
        <div class="border-t border-gray-200">
            <dl class="divide-y divide-gray-200">
                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">IP-adress</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $estimation->ip_address ?? 'Ej tillgänglig' }}</dd>
                </div>
                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Session ID</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $estimation->session_id ?? 'Ej tillgänglig' }}</dd>
                </div>
                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Skapad</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $estimation->created_at->format('Y-m-d H:i:s') }}</dd>
                </div>
                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Uppdaterad</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $estimation->updated_at->format('Y-m-d H:i:s') }}</dd>
                </div>
                @if($estimation->contactMessage)
                    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Kopplat meddelande</dt>
                        <dd class="mt-1 text-sm sm:col-span-2 sm:mt-0">
                            <a href="{{ route('admin.messages.show', $estimation->contactMessage) }}"
                               class="inline-flex items-center text-indigo-600 hover:text-indigo-500">
                                <svg class="mr-1.5 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                                {{ $estimation->contactMessage->name }} ({{ $estimation->contactMessage->email }})
                            </a>
                        </dd>
                    </div>
                @else
                    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Kopplat meddelande</dt>
                        <dd class="mt-1 text-sm text-gray-500 sm:col-span-2 sm:mt-0">Inget kopplat meddelande</dd>
                    </div>
                @endif
            </dl>
        </div>
    </div>
</div>
@endsection
