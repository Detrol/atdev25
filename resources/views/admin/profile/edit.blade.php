@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Profilinställningar</h1>
    <p class="mt-2 text-sm text-gray-700">Hantera din portfolio-profil</p>
</div>

<form action="/admin/profile" method="POST" class="space-y-8 bg-white shadow sm:rounded-lg">
    @csrf
    @method('PUT')
    
    <div class="px-4 py-6 sm:p-8">
        <div class="grid grid-cols-1 gap-6">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Namn *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $profile->name ?? '') }}" required
                    class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 px-3">
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium leading-6 text-gray-900">Titel</label>
                <input type="text" name="title" id="title" value="{{ old('title', $profile->title ?? '') }}"
                    class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 px-3"
                    placeholder="t.ex. Fullstack-utvecklare">
                @error('title')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Bio -->
            <div>
                <label for="bio" class="block text-sm font-medium leading-6 text-gray-900">Bio</label>
                <textarea name="bio" id="bio" rows="5"
                    class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 px-3">{{ old('bio', $profile->bio ?? '') }}</textarea>
                @error('bio')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Contact Info -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">E-post</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $profile->email ?? '') }}"
                        class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 px-3">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium leading-6 text-gray-900">Telefon</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $profile->phone ?? '') }}"
                        class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 px-3">
                    @error('phone')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Social Links -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Sociala länkar</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="github" class="block text-sm font-medium leading-6 text-gray-900">GitHub</label>
                        <input type="url" name="github" id="github" value="{{ old('github', $profile->github ?? '') }}"
                            class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 px-3"
                            placeholder="https://github.com/username">
                        @error('github')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="linkedin" class="block text-sm font-medium leading-6 text-gray-900">LinkedIn</label>
                        <input type="url" name="linkedin" id="linkedin" value="{{ old('linkedin', $profile->linkedin ?? '') }}"
                            class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 px-3"
                            placeholder="https://linkedin.com/in/username">
                        @error('linkedin')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="twitter" class="block text-sm font-medium leading-6 text-gray-900">Twitter/X</label>
                        <input type="url" name="twitter" id="twitter" value="{{ old('twitter', $profile->twitter ?? '') }}"
                            class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 px-3"
                            placeholder="https://twitter.com/username">
                        @error('twitter')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
        <a href="/admin" class="text-sm font-semibold leading-6 text-gray-900">Avbryt</a>
        <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
            Spara Profil
        </button>
    </div>
</form>
@endsection
