@extends('admin.layouts.admin')

@section('title', 'Admin Dashboard')

@section('admin-content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4">Total Books</h3>
        <p class="text-3xl font-bold text-blue-600">{{ \App\Models\Book::count() }}</p>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4">Active Borrows</h3>
        <p class="text-3xl font-bold text-green-600">{{ \App\Models\Borrow::where('status', 'borrowed')->count() }}</p>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4">Pending Requests</h3>
        <p class="text-3xl font-bold text-yellow-600">{{ \App\Models\Borrow::where('status', 'pending')->count() }}</p>
    </div>
</div>

<div class="mt-8">
    <h2 class="text-2xl font-semibold mb-4">Recent Activities</h2>
    <div class="bg-white rounded-lg shadow overflow-hidden">
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
