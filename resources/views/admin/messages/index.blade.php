@extends('layouts.admin')

@section('content')
<div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
        <h1 class="text-2xl font-bold leading-6 text-gray-900">Meddelanden</h1>
        <p class="mt-2 text-sm text-gray-700">Alla kontaktmeddelanden från din portfolio</p>
    </div>
</div>

<div class="mt-8 flow-root">
    <div class="-mx-4 -my-2 sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <div class="shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                @forelse($messages as $message)
                    <div class="bg-white border-b border-gray-200 {{ $message->read_at ? '' : 'bg-indigo-50' }}">
                        <div class="px-4 py-5 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $message->name }}</h3>
                                    @unless($message->read_at)
                                        <span class="inline-flex items-center rounded-md bg-indigo-100 px-2.5 py-0.5 text-xs font-medium text-indigo-800">
                                            Oläst
                                        </span>
                                    @endunless
                                </div>
                                <div class="flex items-center space-x-2">
                                    @unless($message->read_at)
                                        <form action="/admin/messages/{{ $message->id }}/read" method="POST">
                                            @csrf
                                            <button type="submit" class="rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                                Markera som läst
                                            </button>
                                        </form>
                                    @endunless
                                    <form action="/admin/messages/{{ $message->id }}" method="POST" onsubmit="return confirm('Är du säker på att du vill radera detta meddelande?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-md bg-white px-3 py-1.5 text-sm font-semibold text-red-600 shadow-sm ring-1 ring-inset ring-red-300 hover:bg-red-50">
                                            Radera
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="mt-2 sm:flex sm:justify-between">
                                <div class="sm:flex sm:space-x-6">
                                    <p class="flex items-center text-sm text-gray-500">
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
                                <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $message->message }}</p>
                            </div>
                            @if($message->ip_address)
                                <div class="mt-3 text-xs text-gray-500">
                                    IP: {{ $message->ip_address }}
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="bg-white px-4 py-12 text-center">
                        <p class="text-sm text-gray-500">Inga meddelanden ännu</p>
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
