@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
    <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">Välkommen tillbaka till ATDev admin</p>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
    <div class="overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 py-5 shadow dark:shadow-lg sm:p-6 border border-gray-200 dark:border-gray-700">
        <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Totalt Projekt</dt>
        <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900 dark:text-white">{{ $projectsCount }}</dd>
    </div>

    <div class="overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 py-5 shadow dark:shadow-lg sm:p-6 border border-gray-200 dark:border-gray-700">
        <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Tjänster</dt>
        <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900 dark:text-white">{{ $servicesCount }}</dd>
    </div>

    <div class="overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 py-5 shadow dark:shadow-lg sm:p-6 border border-gray-200 dark:border-gray-700">
        <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Olästa Meddelanden</dt>
        <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900 dark:text-white">{{ $unreadMessages }}</dd>
    </div>

    <div class="overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 py-5 shadow dark:shadow-lg sm:p-6 border border-gray-200 dark:border-gray-700">
        <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">Senaste Projekt</dt>
        <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900 dark:text-white">{{ $recentProjects->count() }}</dd>
    </div>
</div>

<!-- Recent Projects -->
<div class="mb-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h2 class="text-lg font-semibold leading-6 text-gray-900 dark:text-white">Senaste Projekt</h2>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">De 5 senast skapade projekten</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <a href="/admin/projects/create" class="block rounded-md bg-indigo-600 dark:bg-indigo-500 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 dark:hover:bg-indigo-400 transition-colors">
                Nytt projekt
            </a>
        </div>
    </div>
    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow dark:shadow-lg ring-1 ring-black dark:ring-gray-700 ring-opacity-5 dark:ring-opacity-100 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-white sm:pl-6">Titel</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Status</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Featured</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Skapad</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Redigera</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                            @forelse($recentProjects as $project)
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white sm:pl-6">
                                        {{ $project->title }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium {{ $project->status->value === 'published' ? 'bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 ring-1 ring-inset ring-green-600/20 dark:ring-green-500/30' : 'bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-300 ring-1 ring-inset ring-gray-500/10 dark:ring-gray-600/50' }}">
                                            {{ $project->status->value === 'published' ? 'Publicerad' : 'Utkast' }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        @if($project->featured)
                                            <span class="inline-flex items-center rounded-md bg-indigo-50 dark:bg-indigo-900/30 px-2 py-1 text-xs font-medium text-indigo-700 dark:text-indigo-400 ring-1 ring-inset ring-indigo-700/10 dark:ring-indigo-500/30">
                                                Featured
                                            </span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $project->created_at->format('Y-m-d') }}
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <a href="/admin/projects/{{ $project->id }}/edit" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">Redigera</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-3 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                        Inga projekt ännu. <a href="/admin/projects/create" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">Skapa ditt första projekt</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Messages -->
<div>
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h2 class="text-lg font-semibold leading-6 text-gray-900 dark:text-white">Senaste Meddelanden</h2>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">De 5 senaste kontaktmeddelandena</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <a href="/admin/messages" class="block rounded-md bg-white dark:bg-gray-800 px-3 py-2 text-center text-sm font-semibold text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                Visa alla
            </a>
        </div>
    </div>
    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow dark:shadow-lg ring-1 ring-black dark:ring-gray-700 ring-opacity-5 dark:ring-opacity-100 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-white sm:pl-6">Namn</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">E-post</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Meddelande</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Datum</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Visa</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                            @forelse($recentMessages as $message)
                                <tr class="{{ $message->read_at ? '' : 'bg-indigo-50 dark:bg-indigo-900/20' }}">
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white sm:pl-6">
                                        {{ $message->name }}
                                        @unless($message->read_at)
                                            <span class="ml-2 inline-flex items-center rounded-md bg-indigo-100 dark:bg-indigo-900/50 px-2 py-1 text-xs font-medium text-indigo-700 dark:text-indigo-300">Ny</span>
                                        @endunless
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $message->email }}
                                    </td>
                                    <td class="px-3 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate">
                                        {{ Str::limit($message->message, 50) }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $message->created_at->format('Y-m-d H:i') }}
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <a href="/admin/messages" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">Visa</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-3 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                        Inga meddelanden ännu
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
