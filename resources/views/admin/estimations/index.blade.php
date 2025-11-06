@extends('layouts.admin')

@section('content')
<div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
        <h1 class="text-2xl font-bold leading-6 text-gray-900">Priskalkyler</h1>
        <p class="mt-2 text-sm text-gray-700">Alla prisuppskattningar som användare har genererat via priskalkylatorn</p>
    </div>
</div>

<div class="mt-8 flow-root">
    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                @forelse($estimations as $estimation)
                    <div class="bg-white border-b border-gray-200 hover:bg-gray-50">
                        <div class="px-4 py-5 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    {{-- Service Category Badge --}}
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
                                    <span class="inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-medium {{ $colorClass }}">
                                        {{ $categoryLabel }}
                                    </span>

                                    {{-- Project Type --}}
                                    <span class="text-sm font-medium text-gray-900">{{ $estimation->project_type_label }}</span>

                                    {{-- Complexity Badge --}}
                                    <span class="inline-flex items-center rounded-md bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">
                                        Komplexitet: {{ $estimation->complexity }}/10
                                    </span>

                                    {{-- Contact Message Link --}}
                                    @if($estimation->contactMessage)
                                        <a href="{{ route('admin.messages.show', $estimation->contactMessage) }}"
                                           class="inline-flex items-center rounded-md bg-indigo-100 px-2.5 py-0.5 text-xs font-medium text-indigo-800 hover:bg-indigo-200"
                                           title="Kopplad till meddelande">
                                            📧 Meddelande
                                        </a>
                                    @endif
                                </div>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.estimations.show', $estimation) }}"
                                       class="rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                        Visa detaljer
                                    </a>
                                    <form action="{{ route('admin.estimations.destroy', $estimation) }}" method="POST"
                                          onsubmit="return confirm('Är du säker på att du vill radera denna prisestimering?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="rounded-md bg-white px-3 py-1.5 text-sm font-semibold text-red-600 shadow-sm ring-1 ring-inset ring-red-300 hover:bg-red-50">
                                            Radera
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
                                {{-- AI Price with VAT --}}
                                <div>
                                    <p class="text-xs text-gray-500">AI-pris (inkl moms)</p>
                                    <p class="mt-1 text-lg font-semibold text-indigo-600">{{ $estimation->price_ai_vat }}</p>
                                </div>
                                {{-- Savings --}}
                                <div>
                                    <p class="text-xs text-gray-500">Besparing ({{ $estimation->savings_percent }}%)</p>
                                    <p class="mt-1 text-lg font-semibold text-green-600">{{ $estimation->savings_vat }}</p>
                                </div>
                                {{-- Created Date --}}
                                <div>
                                    <p class="text-xs text-gray-500">Skapad</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ $estimation->created_at->format('Y-m-d H:i') }}</p>
                                </div>
                            </div>
                            {{-- Description Preview --}}
                            <div class="mt-4">
                                <p class="text-sm text-gray-700 line-clamp-2">{{ $estimation->description }}</p>
                            </div>
                            {{-- IP Address --}}
                            @if($estimation->ip_address)
                                <div class="mt-3 text-xs text-gray-500">
                                    IP: {{ $estimation->ip_address }}
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="bg-white px-4 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900">Inga priskalkyler ännu</h3>
                        <p class="mt-1 text-sm text-gray-500">Priskalkyler som användare skapar via startsidan visas här.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@if($estimations->hasPages())
    <div class="mt-6">
        {{ $estimations->links() }}
    </div>
@endif
@endsection
