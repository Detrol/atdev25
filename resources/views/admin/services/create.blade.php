@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Lägg till tjänst</h1>
            <p class="mt-2 text-sm text-gray-700">Skapa en ny tjänst som visas på hemsidan</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.services.index') }}" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                Tillbaka
            </a>
        </div>
    </div>
</div>

<form action="{{ route('admin.services.store') }}" method="POST" class="space-y-6">
    @csrf

    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6 space-y-6">
            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium leading-6 text-gray-900">
                    Titel <span class="text-red-500">*</span>
                </label>
                <div class="mt-2">
                    <input type="text"
                           name="title"
                           id="title"
                           value="{{ old('title') }}"
                           required
                           class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('title') ring-red-300 @enderror">
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Slug (optional) -->
            <div>
                <label for="slug" class="block text-sm font-medium leading-6 text-gray-900">
                    Slug <span class="text-gray-500 text-xs">(lämnas tom för automatisk generering)</span>
                </label>
                <div class="mt-2">
                    <input type="text"
                           name="slug"
                           id="slug"
                           value="{{ old('slug') }}"
                           class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('slug') ring-red-300 @enderror">
                    @error('slug')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium leading-6 text-gray-900">
                    Beskrivning <span class="text-red-500">*</span>
                </label>
                <div class="mt-2">
                    <textarea name="description"
                              id="description"
                              rows="4"
                              required
                              class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('description') ring-red-300 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Icon -->
            <div>
                <label for="icon" class="block text-sm font-medium leading-6 text-gray-900">
                    Ikon <span class="text-red-500">*</span>
                </label>
                <div class="mt-2">
                    <select name="icon"
                            id="icon"
                            required
                            class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('icon') ring-red-300 @enderror">
                        <option value="">Välj ikon</option>
                        <option value="code" {{ old('icon') === 'code' ? 'selected' : '' }}>Code (Kod)</option>
                        <option value="puzzle-piece" {{ old('icon') === 'puzzle-piece' ? 'selected' : '' }}>Puzzle Piece (Integration)</option>
                        <option value="wrench" {{ old('icon') === 'wrench' ? 'selected' : '' }}>Wrench (Underhåll)</option>
                        <option value="rocket" {{ old('icon') === 'rocket' ? 'selected' : '' }}>Rocket (Launch)</option>
                    </select>
                    @error('icon')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Features -->
            <div>
                <label for="features" class="block text-sm font-medium leading-6 text-gray-900">
                    Funktioner <span class="text-gray-500 text-xs">(valfritt, separera med kommatecken)</span>
                </label>
                <div class="mt-2">
                    <textarea name="features"
                              id="features"
                              rows="3"
                              placeholder="Funktion 1, Funktion 2, Funktion 3"
                              class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('features') ring-red-300 @enderror">{{ old('features') }}</textarea>
                    @error('features')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">En funktion per rad eller separera med kommatecken</p>
                </div>
            </div>

            <!-- Sort Order -->
            <div>
                <label for="sort_order" class="block text-sm font-medium leading-6 text-gray-900">
                    Sorteringsordning
                </label>
                <div class="mt-2">
                    <input type="number"
                           name="sort_order"
                           id="sort_order"
                           value="{{ old('sort_order', 0) }}"
                           min="0"
                           class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('sort_order') ring-red-300 @enderror">
                    @error('sort_order')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Lägre nummer visas först</p>
                </div>
            </div>

            <!-- Is Active -->
            <div class="relative flex items-start">
                <div class="flex h-6 items-center">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox"
                           name="is_active"
                           id="is_active"
                           value="1"
                           {{ old('is_active', true) ? 'checked' : '' }}
                           class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                </div>
                <div class="ml-3 text-sm leading-6">
                    <label for="is_active" class="font-medium text-gray-900">Aktiv</label>
                    <p class="text-gray-500">Visa denna tjänst på hemsidan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex justify-end gap-x-3">
        <a href="{{ route('admin.services.index') }}" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
            Avbryt
        </a>
        <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
            Skapa tjänst
        </button>
    </div>
</form>
@endsection
