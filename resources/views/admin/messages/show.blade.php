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
                <h1 class="text-2xl font-bold text-gray-900">Konversation med {{ $message->name }}</h1>
                <p class="mt-1 text-sm text-gray-500">{{ $message->email }}</p>
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

    {{-- Conversation Thread --}}
    <div class="space-y-4 mb-8">
        @foreach($conversation as $msg)
            <div class="bg-white shadow rounded-lg p-6 {{ $msg->is_admin_reply ? 'border-l-4 border-indigo-500 bg-indigo-50' : '' }}">
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
                            <h3 class="text-sm font-medium text-gray-900">
                                {{ $msg->is_admin_reply ? 'ATDev Admin' : $msg->name }}
                            </h3>
                            <p class="text-xs text-gray-500">
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
    <div class="bg-white shadow rounded-lg p-6">
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
                <p class="text-sm text-gray-500">
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
