@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold leading-6 text-gray-900 dark:text-white">Prisestimering Detaljer</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">Skapad {{ $estimation->created_at->format('Y-m-d H:i') }}</p>
        </div>
        <div class="mt-4 flex space-x-3 sm:mt-0">
            <a href="{{ route('admin.estimations.index') }}"
               class="rounded-md bg-white dark:bg-gray-800 px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800">
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
    <div class="overflow-hidden bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h2 class="text-lg font-semibold leading-6 text-gray-900 dark:text-white">Projektanalys</h2>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-5 sm:px-6">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                {{-- Service Category --}}
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tjänstekategori</dt>
                    <dd class="mt-1">
                        @php
                            $categoryColors = [
                                'web_development' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400',
                                'mobile_app' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-400',
                                'bug_fixes' => 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400',
                                'performance' => 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400',
                                'api_integration' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400',
                                'security' => 'bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-400',
                                'maintenance' => 'bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-300',
                                'modernization' => 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-400',
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
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Projekttyp</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $estimation->project_type_label }}</dd>
                </div>

                {{-- Complexity --}}
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Komplexitet ({{ $estimation->complexity }}/10)</dt>
                    <dd class="mt-2">
                        <div class="flex items-center space-x-2">
                            <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-indigo-600 dark:bg-indigo-500 h-2 rounded-full" style="width: {{ $estimation->complexity * 10 }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $estimation->complexity }}/10</span>
                        </div>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $estimation->complexity_label }}</p>
                    </dd>
                </div>

                {{-- Key Features --}}
                @if($estimation->key_features && count($estimation->key_features) > 0)
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Viktiga funktioner</dt>
                        <dd class="mt-2">
                            <ul class="space-y-1">
                                @foreach($estimation->key_features as $feature)
                                    <li class="flex items-start">
                                        <svg class="h-5 w-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </dd>
                    </div>
                @endif

                {{-- Solution Approach --}}
                @if($estimation->solution_approach)
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500 flex items-center">
                            <svg class="h-5 w-5 text-green-600 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                            Rekommenderad teknisk lösning
                        </dt>
                        <dd class="mt-2">
                            <div class="rounded-lg bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-950/30 dark:to-emerald-950/30 p-4 ring-1 ring-green-200 dark:ring-green-800/50">
                                <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap leading-relaxed">{{ $estimation->solution_approach }}</p>
                            </div>
                        </dd>
                    </div>
                @endif

                {{-- Description --}}
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Projektbeskrivning</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white whitespace-pre-wrap">{{ $estimation->description }}</dd>
                </div>
            </dl>
        </div>
    </div>

    {{-- Section 2: Price Comparison --}}
    <div class="overflow-hidden bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h2 class="text-lg font-semibold leading-6 text-gray-900 dark:text-white">Prisuppskattning</h2>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-5 sm:px-6">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                {{-- Traditional Development --}}
                <div class="rounded-lg bg-gray-50 dark:bg-gray-800/50 p-6 ring-1 ring-gray-200 dark:ring-gray-700">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">Traditionell Utveckling</h3>
                    <dl class="mt-4 space-y-3">
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Timmar</dt>
                            <dd class="text-lg font-semibold text-gray-900 dark:text-white">{{ $estimation->hours_traditional }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Leveranstid</dt>
                            <dd class="text-lg font-semibold text-gray-900 dark:text-white">{{ $estimation->delivery_weeks_traditional }}</dd>
                        </div>
                        <div class="pt-3 border-t border-gray-300 dark:border-gray-600">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Pris (ex moms)</dt>
                            <dd class="text-lg font-semibold text-gray-900 dark:text-white">{{ $estimation->price_traditional }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Pris (inkl 25% moms)</dt>
                            <dd class="text-xl font-bold text-gray-900 dark:text-white">{{ $estimation->price_traditional_vat }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- AI-Driven Development --}}
                <div class="rounded-lg bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-indigo-950/30 dark:to-purple-950/30 p-6 ring-1 ring-indigo-200 dark:ring-indigo-800/50">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">AI-Driven Utveckling</h3>
                        <span class="inline-flex items-center rounded-md bg-green-100 dark:bg-green-900/30 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:text-green-400">
                            {{ $estimation->savings_percent }}% besparing
                        </span>
                    </div>
                    <dl class="mt-4 space-y-3">
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Timmar</dt>
                            <dd class="text-lg font-semibold text-indigo-900 dark:text-indigo-300">{{ $estimation->hours_ai }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Leveranstid</dt>
                            <dd class="text-lg font-semibold text-indigo-900 dark:text-indigo-300">{{ $estimation->delivery_weeks_ai }}</dd>
                        </div>
                        <div class="pt-3 border-t border-indigo-300 dark:border-indigo-700/50">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Pris (ex moms)</dt>
                            <dd class="text-lg font-semibold text-indigo-900 dark:text-indigo-300">{{ $estimation->price_ai }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Pris (inkl 25% moms)</dt>
                            <dd class="text-xl font-bold text-indigo-900 dark:text-indigo-300">{{ $estimation->price_ai_vat }}</dd>
                        </div>
                        <div class="pt-3 border-t border-indigo-300 dark:border-indigo-700/50">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">Du sparar (inkl moms)</dt>
                            <dd class="text-xl font-bold text-green-600 dark:text-green-400">{{ $estimation->savings_vat }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    {{-- Section 3: Metadata --}}
    <div class="overflow-hidden bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h2 class="text-lg font-semibold leading-6 text-gray-900 dark:text-white">Metadata</h2>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700">
            <dl class="divide-y divide-gray-200 dark:divide-gray-700">
                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">IP-adress</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2 sm:mt-0">{{ $estimation->ip_address ?? 'Ej tillgänglig' }}</dd>
                </div>
                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Session ID</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2 sm:mt-0">{{ $estimation->session_id ?? 'Ej tillgänglig' }}</dd>
                </div>
                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Skapad</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2 sm:mt-0">{{ $estimation->created_at->format('Y-m-d H:i:s') }}</dd>
                </div>
                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Uppdaterad</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:col-span-2 sm:mt-0">{{ $estimation->updated_at->format('Y-m-d H:i:s') }}</dd>
                </div>
                @if($estimation->contactMessage)
                    <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kopplat meddelande</dt>
                        <dd class="mt-1 text-sm sm:col-span-2 sm:mt-0">
                            <a href="{{ route('admin.messages.show', $estimation->contactMessage) }}"
                               class="inline-flex items-center text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300">
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
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kopplat meddelande</dt>
                        <dd class="mt-1 text-sm text-gray-500 dark:text-gray-400 sm:col-span-2 sm:mt-0">Inget kopplat meddelande</dd>
                    </div>
                @endif
            </dl>
        </div>
    </div>
</div>
@endsection
