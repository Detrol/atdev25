@extends('layouts.admin')

@section('content')
<div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
        <h1 class="text-2xl font-bold leading-6 text-gray-900 dark:text-white">Meddelanden</h1>
        <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">Alla kontaktmeddelanden från din portfolio</p>
    </div>
</div>

<div class="mt-8 flow-root">
    <div class="-mx-4 -my-2 sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <div class="shadow ring-1 ring-black dark:ring-gray-700 ring-opacity-5 dark:ring-opacity-100 sm:rounded-lg">
                @forelse($messages as $message)
                    <div class="bg-white border-b border-gray-200 {{ $message->read ? '' : 'bg-indigo-50 dark:bg-indigo-900/20' }}">
                        <div class="px-4 py-5 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $message->name }}</h3>
                                    @unless($message->read)
                                        <span class="inline-flex items-center rounded-md bg-indigo-100 px-2.5 py-0.5 text-xs font-medium text-indigo-800">
                                            Oläst
                                        </span>
                                    @endunless

                                    {{-- Status badge --}}
                                    @if($message->status === 'pending')
                                        <span class="inline-flex items-center rounded-md bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">
                                            Väntar
                                        </span>
                                    @elseif($message->status === 'replied')
                                        <span class="inline-flex items-center rounded-md bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                            Besvarad
                                        </span>
                                    @endif

                                    {{-- Reply count --}}
                                    @if($message->replies->count() > 0)
                                        <span class="inline-flex items-center rounded-md bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">
                                            {{ $message->replies->count() }} {{ $message->replies->count() === 1 ? 'svar' : 'svar' }}
                                        </span>
                                    @endif

                                    {{-- Price Estimation indicator --}}
                                    @if($message->priceEstimation)
                                        <span class="inline-flex items-center rounded-md bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-800" title="Har kopplad prisestimering">
                                            📊 Estimering
                                        </span>
                                    @endif
                                </div>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.messages.show', $message) }}" class="rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 dark:hover:bg-indigo-400">
                                        Visa & Svara
                                    </a>
                                    <form action="/admin/messages/{{ $message->id }}" method="POST" onsubmit="return confirm('Är du säker på att du vill radera detta meddelande?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-md bg-white dark:bg-gray-800 px-3 py-1.5 text-sm font-semibold text-red-600 shadow-sm ring-1 ring-inset ring-red-300 hover:bg-red-50">
                                            Radera
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="mt-2 sm:flex sm:justify-between">
                                <div class="sm:flex sm:space-x-6">
                                    <p class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                        <svg class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                        </svg>
                                        {{ $message->email }}
                                    </p>
                                    <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                        <svg class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message->created_at->format('Y-m-d H:i') }}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <p class="text-sm text-gray-700 line-clamp-2">{{ $message->message }}</p>
                            </div>
                            @if($message->ip_address)
                                <div class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                                    IP: {{ $message->ip_address }}
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-gray-800 px-4 py-12 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Inga meddelanden ännu</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@if($messages->hasPages())
    <div class="mt-6">
        {{ $messages->links() }}
    </div>
@endif
@endsection
