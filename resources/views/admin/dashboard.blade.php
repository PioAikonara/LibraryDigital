@extends('admin.layouts.admin')

@section('title', 'Admin Dashboard')

@section('admin-content')
<div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg shadow-lg p-6 mb-6">
    <h1 class="text-3xl font-bold text-white mb-2">Admin Dashboard</h1>
    <p class="text-white opacity-90">Manage your library system</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg shadow-lg p-6 transform transition-all duration-300 hover:scale-105">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xl font-semibold mb-2 text-blue-800">Total Books</h3>
                <p class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">{{ \App\Models\Book::count() }}</p>
            </div>
            <div class="p-3 bg-blue-200 rounded-full">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg shadow-lg p-6 transform transition-all duration-300 hover:scale-105">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xl font-semibold mb-2 text-green-800">Active Borrows</h3>
                <p class="text-3xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">{{ \App\Models\Borrow::where('status', 'borrowed')->count() }}</p>
            </div>
            <div class="p-3 bg-green-200 rounded-full">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg shadow-lg p-6 transform transition-all duration-300 hover:scale-105">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xl font-semibold mb-2 text-yellow-800">Pending Requests</h3>
                <p class="text-3xl font-bold bg-gradient-to-r from-yellow-600 to-amber-600 bg-clip-text text-transparent">{{ \App\Models\Borrow::where('status', 'pending')->count() }}</p>
            </div>
            <div class="p-3 bg-yellow-200 rounded-full">
                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="mt-8">
    <h2 class="text-2xl font-bold mb-4 bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">Recent Activities</h2>
    <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-100">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Book</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
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
                            {{ $borrow->status === 'borrowed' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $borrow->status === 'returned' ? 'bg-gray-100 text-gray-800' : '' }}">
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
