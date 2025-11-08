@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Header --}}
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('admin.messages.index') }}" class="text-sm text-indigo-600 hover:text-indigo-500 mb-2 inline-block">
                    ← Tillbaka till meddelanden
                </a>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Konversation med {{ $message->name }}</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $message->email }}</p>
            </div>
            <div class="flex items-center space-x-2">
                {{-- Status badge --}}
                @if($message->status === 'pending')
                    <span class="inline-flex items-center rounded-md bg-yellow-100 px-3 py-1 text-sm font-medium text-yellow-800">
                        Väntar på svar
                    </span>
                @elseif($message->status === 'replied')
                    <span class="inline-flex items-center rounded-md bg-green-100 px-3 py-1 text-sm font-medium text-green-800">
                        Besvarad
                    </span>
                @else
                    <span class="inline-flex items-center rounded-md bg-gray-100 px-3 py-1 text-sm font-medium text-gray-800">
                        Stängd
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- Price Estimation (if linked) --}}
    @if($message->priceEstimation)
    <div class="mb-6 bg-gradient-to-br from-purple-50 to-blue-50 rounded-lg p-6 border-2 border-purple-200">
        <div class="flex items-center gap-3 mb-4">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
            </svg>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Kopplad Prisestimering</h2>
        </div>

        <div class="grid md:grid-cols-4 gap-4 mb-4">
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <p class="text-xs font-semibold text-purple-600 mb-1">Projekttyp</p>
                <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $message->priceEstimation->project_type_label }}</p>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <p class="text-xs font-semibold text-blue-600 mb-1">Komplexitet</p>
                <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $message->priceEstimation->complexity }}/10</p>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <p class="text-xs font-semibold text-gray-600 mb-1">Arbetstid (AI)</p>
                <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $message->priceEstimation->hours_ai }}</p>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <p class="text-xs font-semibold text-gray-600 mb-1">Leverans</p>
                <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $message->priceEstimation->delivery_weeks_ai }}</p>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4 mb-4">
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <p class="text-xs font-semibold text-gray-600 mb-2">Traditionell Utveckling</p>
                <div class="space-y-1 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Timmar:</span>
                        <span class="font-semibold">{{ $message->priceEstimation->hours_traditional }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Pris (ex. moms):</span>
                        <span class="font-semibold">{{ $message->priceEstimation->price_traditional }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Inkl. moms:</span>
                        <span class="font-bold text-gray-900 dark:text-white">{{ $message->priceEstimation->price_traditional_vat }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-600 to-blue-600 text-white rounded-lg p-4 shadow-sm">
                <p class="text-xs font-semibold opacity-90 mb-2">AI-Driven (ATDev) · -50%</p>
                <div class="space-y-1 text-sm">
                    <div class="flex justify-between">
                        <span class="opacity-90">Timmar:</span>
                        <span class="font-semibold">{{ $message->priceEstimation->hours_ai }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="opacity-90">Pris (ex. moms):</span>
                        <span class="font-semibold">{{ $message->priceEstimation->price_ai }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="opacity-90">Inkl. moms:</span>
                        <span class="font-bold text-xl">{{ $message->priceEstimation->price_ai_vat }}</span>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-white/30">
                    <div class="flex justify-between">
                        <span class="text-xs opacity-90">Besparing:</span>
                        <span class="font-bold">{{ $message->priceEstimation->savings_vat }}</span>
                    </div>
                </div>
            </div>
        </div>

        @if(count($message->priceEstimation->key_features) > 0)
        <div class="bg-white rounded-lg p-4 shadow-sm">
            <p class="text-xs font-semibold text-gray-700 mb-2">Identifierade Funktioner:</p>
            <div class="flex flex-wrap gap-2">
                @foreach($message->priceEstimation->key_features as $feature)
                    <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-lg text-xs font-medium">{{ $feature }}</span>
                @endforeach
            </div>
        </div>
        @endif

        <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
            <p class="text-xs text-gray-700 dark:text-gray-300"><strong>Original beskrivning:</strong></p>
            <p class="text-sm text-gray-600 mt-1">{{ $message->priceEstimation->description }}</p>
        </div>
    </div>
    @endif

    {{-- Conversation Thread --}}
    <div class="space-y-4 mb-8">
        @foreach($conversation as $msg)
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 {{ $msg->is_admin_reply ? 'border-l-4 border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : '' }}">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center space-x-3">
                        {{-- Avatar --}}
                        <div class="flex-shrink-0">
                            @if($msg->is_admin_reply)
                                <div class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center">
                                    <span class="text-white font-medium">AT</span>
                                </div>
                            @else
                                <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                    <span class="text-gray-600 font-medium">{{ substr($msg->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $msg->is_admin_reply ? 'ATDev Admin' : $msg->name }}
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $msg->created_at->format('Y-m-d H:i') }}
                                @if($msg->is_admin_reply && $msg->adminReplier)
                                    · Av {{ $msg->adminReplier->name }}
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($msg->is_admin_reply)
                        <span class="inline-flex items-center rounded-md bg-indigo-100 px-2.5 py-0.5 text-xs font-medium text-indigo-800">
                            Ditt svar
                        </span>
                    @endif
                </div>

                <div class="mt-4 text-sm text-gray-700 whitespace-pre-wrap">{{ $msg->message }}</div>
            </div>
        @endforeach
    </div>

    {{-- Reply Form --}}
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Svara på meddelande</h2>

        <form action="{{ route('admin.messages.reply', $message) }}" method="POST" x-data="{ message: '' }">
            @csrf

            <div class="mb-4">
                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                    Ditt svar
                </label>
                <textarea
                    id="message"
                    name="message"
                    rows="6"
                    x-model="message"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('message') border-red-300 @enderror"
                    placeholder="Skriv ditt svar här..."
                >{{ old('message') }}</textarea>

                @error('message')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <p class="mt-2 text-sm text-gray-500" x-show="message.length > 0">
                    <span x-text="message.length"></span> / 5000 tecken
                </p>
            </div>

            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Svaret skickas via email till {{ $message->email }}
                </p>
                <button
                    type="submit"
                    class="inline-flex justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                >
                    Skicka svar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
