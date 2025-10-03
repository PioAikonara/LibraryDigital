@extends('petugas.layouts.petugas')

@section('title', 'Petugas Dashboard')

@section('petugas-content')
<div class="bg-gradient-to-r from-cyan-600 to-teal-600 rounded-lg shadow-lg p-6 mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Petugas Dashboard</h1>
            <p class="text-white opacity-90">Manage library operations</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg shadow-lg p-6 transform transition-all duration-300 hover:scale-105">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xl font-semibold mb-2 text-blue-800">Active Borrows</h3>
                <p class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">{{ \App\Models\Borrow::where('status', 'borrowed')->count() }}</p>
            </div>
            <div class="p-3 bg-blue-200 rounded-full">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg shadow-lg p-6 transform transition-all duration-300 hover:scale-105">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xl font-semibold mb-2 text-yellow-800">Pending Returns</h3>
                <p class="text-3xl font-bold bg-gradient-to-r from-yellow-600 to-amber-600 bg-clip-text text-transparent">{{ \App\Models\Borrow::where('status', 'borrowed')->whereDate('return_date', '<', now())->count() }}</p>
            </div>
            <div class="p-3 bg-yellow-200 rounded-full">
                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg shadow-lg p-6 transform transition-all duration-300 hover:scale-105">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xl font-semibold mb-2 text-green-800">Books Available</h3>
                <p class="text-3xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">{{ \App\Models\Book::where('stock', '>', 0)->count() }}</p>
            </div>
            <div class="p-3 bg-green-200 rounded-full">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="mt-8">
    <h2 class="text-2xl font-bold mb-4 bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">Recent Activities</h2>
    <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
        <table class="min-w-full">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Book</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach(\App\Models\Borrow::with(['user', 'book'])->latest()->take(5)->get() as $borrow)
                <tr>
                    <td class="px-6 py-4">{{ $borrow->user->name }}</td>
                    <td class="px-6 py-4">{{ $borrow->book->title }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-sm rounded-full 
                            {{ $borrow->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $borrow->status === 'borrowed' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $borrow->status === 'returned' ? 'bg-green-100 text-green-800' : '' }}">
                            {{ ucfirst($borrow->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ $borrow->created_at->format('Y-m-d') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection