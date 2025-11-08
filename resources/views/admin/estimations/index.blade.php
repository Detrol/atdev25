@extends('layouts.admin')

@section('content')
<div x-data="{
    selectedIds: [],
    selectAll: false,
    toggleSelectAll() {
        if (this.selectAll) {
            this.selectedIds = {{ $estimations->pluck('id')->toJson() }};
        } else {
            this.selectedIds = [];
        }
    },
    toggleSelection(id) {
        const index = this.selectedIds.indexOf(id);
        if (index > -1) {
            this.selectedIds.splice(index, 1);
        } else {
            this.selectedIds.push(id);
        }
        this.selectAll = this.selectedIds.length === {{ $estimations->count() }};
    },
    isSelected(id) {
        return this.selectedIds.includes(id);
    },
    clearSelection() {
        this.selectedIds = [];
        this.selectAll = false;
    }
}">
    {{-- Sticky Toolbar (visas när items är valda) --}}
    <div x-show="selectedIds.length > 0"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="fixed top-16 left-0 right-0 z-30 lg:left-72 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm"
         style="display: none;">
        <div class="px-4 py-3 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        <span x-text="selectedIds.length"></span> <span x-text="selectedIds.length === 1 ? 'kalkyl vald' : 'kalkyler valda'"></span>
                    </span>
                </div>
                <div class="flex items-center space-x-2">
                    <button type="button"
                            @click="clearSelection()"
                            class="rounded-md bg-white dark:bg-gray-800 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                        Avmarkera alla
                    </button>
                    <form action="{{ route('admin.estimations.bulk-destroy') }}" method="POST"
                          @submit="return confirm(`Är du säker på att du vill radera ${selectedIds.length} ${selectedIds.length === 1 ? 'prisestimering' : 'prisertimeringar'}?`);">
                        @csrf
                        @method('DELETE')
                        <template x-for="id in selectedIds" :key="id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>
                        <button type="submit"
                                class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500">
                            Radera valda
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Header --}}
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-bold leading-6 text-gray-900 dark:text-white">Priskalkyler</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">Alla prisuppskattningar som användare har genererat via priskalkylatorn</p>
        </div>
    </div>

    {{-- Select All Checkbox --}}
    @if($estimations->count() > 0)
        <div class="mt-4 flex items-center">
            <input type="checkbox"
                   x-model="selectAll"
                   @change="toggleSelectAll()"
                   id="select-all"
                   class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-800 text-indigo-600 focus:ring-indigo-600 dark:focus:ring-indigo-400 cursor-pointer">
            <label for="select-all" class="ml-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                Välj alla
            </label>
        </div>
    @endif

    {{-- List --}}
    <div class="mt-4 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black dark:ring-gray-700 ring-opacity-5 dark:ring-opacity-100 sm:rounded-lg">
                    @forelse($estimations as $estimation)
                        <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50"
                             :class="{ 'bg-indigo-50 dark:bg-indigo-900/20': isSelected({{ $estimation->id }}) }">
                            <div class="px-4 py-5 sm:px-6">
                                <div class="flex items-start space-x-3">
                                    {{-- Checkbox --}}
                                    <div class="flex items-center h-6">
                                        <input type="checkbox"
                                               :checked="isSelected({{ $estimation->id }})"
                                               @change="toggleSelection({{ $estimation->id }})"
                                               class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 cursor-pointer"
                                               aria-label="Välj denna prisestimering">
                                    </div>

                                    {{-- Content --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center flex-wrap gap-2">
                                                {{-- Service Category Badge --}}
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
                                                <span class="inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-medium {{ $colorClass }}">
                                                    {{ $categoryLabel }}
                                                </span>

                                                {{-- Project Type --}}
                                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $estimation->project_type_label }}</span>

                                                {{-- Complexity Badge --}}
                                                <span class="inline-flex items-center rounded-md bg-gray-100 dark:bg-gray-800 px-2.5 py-0.5 text-xs font-medium text-gray-800 dark:text-gray-300">
                                                    Komplexitet: {{ $estimation->complexity }}/10
                                                </span>

                                                {{-- Contact Message Link --}}
                                                @if($estimation->contactMessage)
                                                    <a href="{{ route('admin.messages.show', $estimation->contactMessage) }}"
                                                       class="inline-flex items-center rounded-md bg-indigo-100 dark:bg-indigo-900/30 px-2.5 py-0.5 text-xs font-medium text-indigo-800 dark:text-indigo-400 hover:bg-indigo-200 dark:hover:bg-indigo-900/50"
                                                       title="Kopplad till meddelande">
                                                        📧 Meddelande
                                                    </a>
                                                @endif
                                            </div>
                                            <div class="flex items-center space-x-2 ml-4 flex-shrink-0">
                                                <a href="{{ route('admin.estimations.show', $estimation) }}"
                                                   class="rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 dark:hover:bg-indigo-400">
                                                    Visa detaljer
                                                </a>
                                                <form action="{{ route('admin.estimations.destroy', $estimation) }}" method="POST"
                                                      onsubmit="return confirm('Är du säker på att du vill radera denna prisestimering?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="rounded-md bg-white dark:bg-gray-800 px-3 py-1.5 text-sm font-semibold text-red-600 shadow-sm ring-1 ring-inset ring-red-300 hover:bg-red-50">
                                                        Radera
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
                                            {{-- AI Price with VAT --}}
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">AI-pris (inkl moms)</p>
                                                <p class="mt-1 text-lg font-semibold text-indigo-600">{{ $estimation->price_ai_vat }}</p>
                                            </div>
                                            {{-- Savings --}}
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Besparing ({{ $estimation->savings_percent }}%)</p>
                                                <p class="mt-1 text-lg font-semibold text-green-600">{{ $estimation->savings_vat }}</p>
                                            </div>
                                            {{-- Created Date --}}
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Skapad</p>
                                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $estimation->created_at->format('Y-m-d H:i') }}</p>
                                            </div>
                                        </div>
                                        {{-- Description Preview --}}
                                        <div class="mt-4">
                                            <p class="text-sm text-gray-700 dark:text-gray-300 line-clamp-2">{{ $estimation->description }}</p>
                                        </div>
                                        {{-- IP Address --}}
                                        @if($estimation->ip_address)
                                            <div class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                                                IP: {{ $estimation->ip_address }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white dark:bg-gray-800 px-4 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">Inga priskalkyler ännu</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Priskalkyler som användare skapar via startsidan visas här.</p>
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
</div>
@endsection
