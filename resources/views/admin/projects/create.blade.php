@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Skapa Nytt Projekt</h1>
    <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">Lägg till ett nytt projekt i din portfolio</p>
</div>

<form action="/admin/projects" method="POST" class="space-y-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
    @csrf
    
    <div class="px-4 py-6 sm:p-8">
        <div class="grid grid-cols-1 gap-6">
            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">Titel *</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                    class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3">
                @error('title')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Slug -->
            <div>
                <label for="slug" class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">Slug</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                    class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3">
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Lämna tomt för att generera automatiskt från titeln</p>
                @error('slug')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Summary -->
            <div>
                <label for="summary" class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">Sammanfattning</label>
                <textarea name="summary" id="summary" rows="3"
                    class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3">{{ old('summary') }}</textarea>
                @error('summary')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">Beskrivning</label>
                <textarea name="description" id="description" rows="8"
                    class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Technologies -->
            <div>
                <label for="technologies" class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">Teknologier</label>
                <input type="text" name="technologies" id="technologies" value="{{ old('technologies') }}"
                    class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3"
                    placeholder="Laravel, Vue.js, Tailwind CSS">
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Separera med kommatecken</p>
                @error('technologies')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Client & Testimonial -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="client_name" class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">Klientnamn</label>
                    <input type="text" name="client_name" id="client_name" value="{{ old('client_name') }}"
                        class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3"
                        placeholder="Företag AB">
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Valfritt - visas bara om ifyllt</p>
                    @error('client_name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="testimonial" class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">Testimonial</label>
                    <textarea name="testimonial" id="testimonial" rows="3"
                        class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3"
                        placeholder="Ett fantastiskt projekt...">{{ old('testimonial') }}</textarea>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Valfritt - visas bara om ifyllt</p>
                    @error('testimonial')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- URLs -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="live_url" class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">Live URL</label>
                    <input type="url" name="live_url" id="live_url" value="{{ old('live_url') }}"
                        class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3"
                        placeholder="https://example.com">
                    @error('live_url')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="repo_url" class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">Repository URL</label>
                    <input type="url" name="repo_url" id="repo_url" value="{{ old('repo_url') }}"
                        class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3"
                        placeholder="https://github.com/...">
                    @error('repo_url')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Status and Options -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                <div>
                    <label for="status" class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">Status *</label>
                    <select name="status" id="status" required
                        class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3">
                        @foreach($statuses as $status)
                            <option value="{{ $status->value }}" {{ old('status') === $status->value ? 'selected' : '' }}>
                                {{ $status->value === 'published' ? 'Publicerad' : 'Utkast' }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="featured" class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">Featured</label>
                    <div class="mt-2">
                        <input type="checkbox" name="featured" id="featured" value="1" {{ old('featured') ? 'checked' : '' }}
                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                        <span class="ml-2 text-sm text-gray-600">Visa på startsidan</span>
                    </div>
                </div>

                <div>
                    <label for="sort_order" class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">Sorteringsordning</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                        class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3">
                    @error('sort_order')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
        <a href="/admin/projects" class="text-sm font-semibold leading-6 text-gray-900 dark:text-white">Avbryt</a>
        <button type="submit" class="rounded-md bg-indigo-600 dark:bg-indigo-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
            Skapa Projekt
        </button>
    </div>
</form>
@endsection
