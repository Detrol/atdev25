@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Redigera Projekt</h1>
    <p class="mt-2 text-sm text-gray-700">Uppdatera projektinformation</p>
</div>

<form action="/admin/projects/{{ $project->slug }}" method="POST" class="space-y-8 bg-white shadow sm:rounded-lg">
    @csrf
    @method('PUT')
    
    <div class="px-4 py-6 sm:p-8">
        <div class="grid grid-cols-1 gap-6">
            <div>
                <label for="title" class="block text-sm font-medium leading-6 text-gray-900">Titel *</label>
                <input type="text" name="title" id="title" value="{{ old('title', $project->title) }}" required
                    class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 px-3">
                @error('title')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="slug" class="block text-sm font-medium leading-6 text-gray-900">Slug</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $project->slug) }}"
                    class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 px-3">
                @error('slug')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="summary" class="block text-sm font-medium leading-6 text-gray-900">Sammanfattning</label>
                <textarea name="summary" id="summary" rows="3"
                    class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 px-3">{{ old('summary', $project->summary) }}</textarea>
                @error('summary')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium leading-6 text-gray-900">Beskrivning</label>
                <textarea name="description" id="description" rows="8"
                    class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 px-3">{{ old('description', $project->description) }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="technologies" class="block text-sm font-medium leading-6 text-gray-900">Teknologier</label>
                <input type="text" name="technologies" id="technologies"
                    value="{{ $technologiesString }}"
                    class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 px-3"
                    placeholder="Laravel, Vue.js, Tailwind CSS">
                <p class="mt-2 text-sm text-gray-500">Separera med kommatecken</p>
                @error('technologies')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="live_url" class="block text-sm font-medium leading-6 text-gray-900">Live URL</label>
                    <input type="url" name="live_url" id="live_url" value="{{ old('live_url', $project->live_url) }}"
                        class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 px-3">
                    @error('live_url')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="repo_url" class="block text-sm font-medium leading-6 text-gray-900">Repository URL</label>
                    <input type="url" name="repo_url" id="repo_url" value="{{ old('repo_url', $project->repo_url) }}"
                        class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 px-3">
                    @error('repo_url')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                <div>
                    <label for="status" class="block text-sm font-medium leading-6 text-gray-900">Status *</label>
                    <select name="status" id="status" required
                        class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 px-3">
                        @foreach($statuses as $status)
                            <option value="{{ $status->value }}" {{ old('status', $project->status->value) === $status->value ? 'selected' : '' }}>
                                {{ $status->value === 'published' ? 'Publicerad' : 'Utkast' }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="featured" class="block text-sm font-medium leading-6 text-gray-900">Featured</label>
                    <div class="mt-2">
                        <input type="checkbox" name="featured" id="featured" value="1" {{ old('featured', $project->featured) ? 'checked' : '' }}
                            class="h-4 w-4 rounded border-gray-300 text-indigo-600">
                        <span class="ml-2 text-sm text-gray-600">Visa på startsidan</span>
                    </div>
                </div>

                <div>
                    <label for="sort_order" class="block text-sm font-medium leading-6 text-gray-900">Sorteringsordning</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $project->sort_order) }}" min="0"
                        class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 px-3">
                    @error('sort_order')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            @if($project->screenshot_path)
                <div>
                    <label class="block text-sm font-medium leading-6 text-gray-900 mb-2">Nuvarande Screenshot</label>
                    <img src="{{ asset('storage/' . $project->screenshot_path) }}" alt="Screenshot" class="max-w-md rounded-lg shadow-lg">
                    <form action="/admin/projects/{{ $project->slug }}/screenshot" method="POST" class="mt-4">
                        @csrf
                        <button type="submit" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            Uppdatera Screenshot
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
        <a href="/admin/projects" class="text-sm font-semibold leading-6 text-gray-900">Avbryt</a>
        <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
            Uppdatera Projekt
        </button>
    </div>
</form>
@endsection
