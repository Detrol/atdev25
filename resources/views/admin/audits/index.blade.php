@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Website Audits</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Hantera alla website audits</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-lg p-6 border border-gray-200 dark:border-gray-700">
            <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Totalt</div>
            <div class="text-3xl font-bold text-gray-800 dark:text-white">{{ $stats['total'] }}</div>
        </div>
        <div class="bg-green-50 dark:bg-green-900/30 rounded-lg shadow dark:shadow-lg p-6 border border-green-100 dark:border-green-800/50">
            <div class="text-sm text-green-600 dark:text-green-400 mb-1">Klara</div>
            <div class="text-3xl font-bold text-green-700 dark:text-green-400">{{ $stats['completed'] }}</div>
        </div>
        <div class="bg-blue-50 dark:bg-blue-900/30 rounded-lg shadow dark:shadow-lg p-6 border border-blue-100 dark:border-blue-800/50">
            <div class="text-sm text-blue-600 dark:text-blue-400 mb-1">Bearbetas</div>
            <div class="text-3xl font-bold text-blue-700 dark:text-blue-400">{{ $stats['processing'] }}</div>
        </div>
        <div class="bg-yellow-50 dark:bg-yellow-900/30 rounded-lg shadow dark:shadow-lg p-6 border border-yellow-100 dark:border-yellow-800/50">
            <div class="text-sm text-yellow-600 dark:text-yellow-400 mb-1">Väntar</div>
            <div class="text-3xl font-bold text-yellow-700 dark:text-yellow-400">{{ $stats['pending'] }}</div>
        </div>
        <div class="bg-purple-50 dark:bg-purple-900/30 rounded-lg shadow dark:shadow-lg p-6 border border-purple-100 dark:border-purple-800/50">
            <div class="text-sm text-purple-600 dark:text-purple-400 mb-1">Snittbetyg</div>
            <div class="text-3xl font-bold text-purple-700 dark:text-purple-400">{{ $stats['avg_score'] ? round($stats['avg_score']) : '-' }}</div>
        </div>
    </div>

    <!-- Audits Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">URL</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Betyg</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Skapad</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Åtgärder</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($audits as $audit)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $audit->url }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $audit->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if($audit->status === 'completed') bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400
                            @elseif($audit->status === 'processing') bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400
                            @elseif($audit->status === 'pending') bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400
                            @else bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400
                            @endif">
                            {{ $audit->status_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                        @if($audit->isCompleted())
                        <span class="font-semibold">{{ $audit->overall_score }}/100</span>
                        @else
                        -
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        {{ $audit->created_at->diffForHumans() }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.audits.show', $audit) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-3">Visa</a>
                        <form action="{{ route('admin.audits.destroy', $audit) }}" method="POST" class="inline" onsubmit="return confirm('Är du säker?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">Radera</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">Inga audits ännu</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if($audits->hasPages())
        <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200">
            {{ $audits->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
