@extends('layouts.admin')

@section('head')
<style>
    .code-editor {
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', 'Consolas', 'source-code-pro', monospace;
        font-size: 13px;
        line-height: 1.5;
        tab-size: 4;
    }
    .preview-content {
        min-height: 200px;
        max-height: 600px;
        overflow-y: auto;
    }
</style>
@endsection

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Redigera FAQ</h1>
    <p class="mt-2 text-sm text-gray-700">Uppdatera frågan och svaret</p>
</div>

<form action="{{ route('admin.faqs.update', $faq) }}" method="POST" class="space-y-8 bg-white shadow sm:rounded-lg">
    @csrf
    @method('PUT')

    <div class="px-4 py-6 sm:p-8">
        <div class="grid grid-cols-1 gap-6">
            <!-- Question -->
            <div>
                <label for="question" class="block text-sm font-medium leading-6 text-gray-900">Fråga *</label>
                <input type="text" name="question" id="question" value="{{ old('question', $faq->question) }}" required
                    class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3"
                    placeholder="Vad kostar det att bygga en webbplats?">
                @error('question')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Answer (HTML Editor with Preview) -->
            <div x-data="{ activeTab: 'edit' }">
                <label for="answer" class="block text-sm font-medium leading-6 text-gray-900 mb-2">Svar *</label>

                <!-- Tabs -->
                <div class="border-b border-gray-200 mb-4">
                    <nav class="-mb-px flex space-x-8">
                        <button
                            type="button"
                            @click="activeTab = 'edit'"
                            :class="activeTab === 'edit' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'"
                            class="whitespace-nowrap border-b-2 py-2 px-1 text-sm font-medium">
                            Redigera HTML
                        </button>
                        <button
                            type="button"
                            @click="activeTab = 'preview'"
                            :class="activeTab === 'preview' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'"
                            class="whitespace-nowrap border-b-2 py-2 px-1 text-sm font-medium">
                            Förhandsgranska
                        </button>
                    </nav>
                </div>

                <!-- Edit Tab -->
                <div x-show="activeTab === 'edit'">
                    <textarea
                        name="answer"
                        id="answer"
                        rows="20"
                        required
                        class="code-editor mt-2 block w-full rounded-md border-0 py-3 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                        placeholder="<div>HTML-innehåll här...</div>">{{ old('answer', $faq->answer) }}</textarea>
                    <p class="mt-2 text-sm text-gray-500">
                        <strong>Tips:</strong> Redigera HTML direkt inklusive SVG-ikoner, Tailwind-klasser och alla andra element.
                    </p>
                </div>

                <!-- Preview Tab -->
                <div x-show="activeTab === 'preview'" x-cloak>
                    <div class="preview-content rounded-md border border-gray-300 bg-white p-6 shadow-sm">
                        <div x-html="document.getElementById('answer').value"></div>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">
                        <strong>OBS:</strong> Detta är en förhandsgranskning. Klicka på "Redigera HTML" för att göra ändringar.
                    </p>
                </div>

                @error('answer')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tags -->
            <div>
                <label for="tags" class="block text-sm font-medium leading-6 text-gray-900">Taggar</label>
                <input type="text" name="tags" id="tags" value="{{ old('tags', $tagsString) }}"
                    class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3"
                    placeholder="wordpress, priser, support">
                <p class="mt-2 text-sm text-gray-500">Separera med kommatecken (valfritt)</p>
                @error('tags')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Sort Order -->
            <div>
                <label for="sort_order" class="block text-sm font-medium leading-6 text-gray-900">Sorteringsordning</label>
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $faq->sort_order) }}" min="0"
                    class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3">
                <p class="mt-2 text-sm text-gray-500">Lägre nummer visas först</p>
                @error('sort_order')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Checkboxes for visibility -->
            <div class="space-y-4">
                <h3 class="text-sm font-medium text-gray-900">Synlighet</h3>

                <!-- Active -->
                <div class="flex items-center">
                    <input type="checkbox" name="active" id="active" value="1"
                        {{ old('active', $faq->active) ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                    <label for="active" class="ml-2 block text-sm text-gray-900">
                        Aktiv (visar FAQ om aktiverad)
                    </label>
                </div>

                <!-- Show in Public FAQ -->
                <div class="flex items-center">
                    <input type="checkbox" name="show_in_public_faq" id="show_in_public_faq" value="1"
                        {{ old('show_in_public_faq', $faq->show_in_public_faq) ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                    <label for="show_in_public_faq" class="ml-2 block text-sm text-gray-900">
                        Visa på FAQ-sidan
                    </label>
                </div>

                <!-- Show in AI Chat -->
                <div class="flex items-center">
                    <input type="checkbox" name="show_in_ai_chat" id="show_in_ai_chat" value="1"
                        {{ old('show_in_ai_chat', $faq->show_in_ai_chat) ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                    <label for="show_in_ai_chat" class="ml-2 block text-sm text-gray-900">
                        Mata till AI Chat (assistenten får denna kunskap)
                    </label>
                </div>

                <!-- Show in Price Calculator -->
                <div class="flex items-center">
                    <input type="checkbox" name="show_in_price_calculator" id="show_in_price_calculator" value="1"
                        {{ old('show_in_price_calculator', $faq->show_in_price_calculator) ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                    <label for="show_in_price_calculator" class="ml-2 block text-sm text-gray-900">
                        Mata till Priskalkylator (AI får denna kunskap vid prisuppskattningar)
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="flex items-center justify-between gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
        <a href="{{ route('admin.faqs.index') }}" class="text-sm font-semibold leading-6 text-gray-900">
            Avbryt
        </a>
        <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
            Uppdatera FAQ
        </button>
    </div>
</form>
@endsection
