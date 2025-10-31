@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Website Audits</h1>
            <p class="text-gray-600 mt-2">Hantera alla website audits</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-600 mb-1">Totalt</div>
            <div class="text-3xl font-bold text-gray-800">{{ $stats['total'] }}</div>
        </div>
        <div class="bg-green-50 rounded-lg shadow p-6">
            <div class="text-sm text-green-600 mb-1">Klara</div>
            <div class="text-3xl font-bold text-green-700">{{ $stats['completed'] }}</div>
        </div>
        <div class="bg-blue-50 rounded-lg shadow p-6">
            <div class="text-sm text-blue-600 mb-1">Bearbetas</div>
            <div class="text-3xl font-bold text-blue-700">{{ $stats['processing'] }}</div>
        </div>
        <div class="bg-yellow-50 rounded-lg shadow p-6">
            <div class="text-sm text-yellow-600 mb-1">Väntar</div>
            <div class="text-3xl font-bold text-yellow-700">{{ $stats['pending'] }}</div>
        </div>
        <div class="bg-purple-50 rounded-lg shadow p-6">
            <div class="text-sm text-purple-600 mb-1">Snittbetyg</div>
            <div class="text-3xl font-bold text-purple-700">{{ $stats['avg_score'] ? round($stats['avg_score']) : '-' }}</div>
        </div>
    </div>

    <!-- Audits Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">URL</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Betyg</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skapad</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Åtgärder</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($audits as $audit)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $audit->url }}</div>
                        <div class="text-sm text-gray-500">{{ $audit->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if($audit->status === 'completed') bg-green-100 text-green-800
                            @elseif($audit->status === 'processing') bg-blue-100 text-blue-800
                            @elseif($audit->status === 'pending') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ $audit->status_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        @if($audit->isCompleted())
                        <span class="font-semibold">{{ $audit->overall_score }}/100</span>
                        @else
                        -
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $audit->created_at->diffForHumans() }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.audits.show', $audit) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Visa</a>
                        <form action="{{ route('admin.audits.destroy', $audit) }}" method="POST" class="inline" onsubmit="return confirm('Är du säker?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Radera</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">Inga audits ännu</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if($audits->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $audits->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
