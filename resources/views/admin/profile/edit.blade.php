@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Profilinställningar</h1>
    <p class="mt-2 text-sm text-gray-700">Hantera din portfolio-profil</p>
</div>

<form action="/admin/profile" method="POST" enctype="multipart/form-data" class="space-y-8 bg-white shadow sm:rounded-lg">
    @csrf
    @method('PUT')
    
    <div class="px-4 py-6 sm:p-8">
        <div class="grid grid-cols-1 gap-6">
            <!-- Avatar (Profilbild) -->
            <div>
                <label class="block text-sm font-medium leading-6 text-gray-900">Profilbild (Avatar)</label>
                <div class="mt-2 flex items-center gap-4">
                    @if($profile->exists && $profile->hasMedia('avatar'))
                        <img src="{{ $profile->getFirstMediaUrl('avatar', 'thumb') }}" alt="Avatar" class="h-24 w-24 rounded-full object-cover border-2 border-gray-200">
                    @else
                        <div class="h-24 w-24 rounded-full bg-gray-200 flex items-center justify-center">
                            <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    @endif
                    <div class="flex-1">
                        <input type="file" name="avatar" id="avatar" accept="image/*"
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                        <p class="mt-1 text-xs text-gray-500">PNG, JPG, WEBP upp till 5MB (optimeras automatiskt)</p>
                        @if($profile->exists && $profile->hasMedia('avatar'))
                            <div class="mt-2 flex items-center">
                                <input type="checkbox" name="remove_avatar" id="remove_avatar" value="1"
                                    class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-600">
                                <label for="remove_avatar" class="ml-2 text-sm text-gray-700">Ta bort nuvarande bild</label>
                            </div>
                        @endif
                    </div>
                </div>
                @error('avatar')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Arbetsbild (Om Mig-sektion) -->
            <div>
                <label class="block text-sm font-medium leading-6 text-gray-900">Arbetsbild (Om Mig-sektion)</label>
                <div class="mt-2">
                    @if($profile->exists && $profile->hasMedia('work_image'))
                        <div class="max-w-md mx-auto mb-4">
                            <img src="{{ $profile->getFirstMediaUrl('work_image', 'optimized') }}" alt="Arbetsbild" class="rounded-2xl shadow-lg border border-gray-200">
                        </div>
                    @endif
                    <input type="file" name="hero_image" id="hero_image" accept="image/*"
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                    <p class="mt-1 text-xs text-gray-500">PNG, JPG, WEBP upp till 5MB. Visas i "Om Mig"-sektionen (optimeras automatiskt)</p>
                    @if($profile->exists && $profile->hasMedia('work_image'))
                        <div class="mt-2 flex items-center">
                            <input type="checkbox" name="remove_hero_image" id="remove_hero_image" value="1"
                                class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-600">
                            <label for="remove_hero_image" class="ml-2 text-sm text-gray-700">Ta bort nuvarande bild</label>
                        </div>
                    @endif
                </div>
                @error('hero_image')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
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
