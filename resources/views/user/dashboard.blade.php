@extends('user.layouts.user')

@section('title', 'User Dashboard')

@section('user-content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4">My Active Borrows</h3>
        <p class="text-3xl font-bold text-blue-600">
            {{ Auth::user()->borrows()->where('status', 'borrowed')->count() }}
        </p>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-semibold mb-4">Pending Requests</h3>
        <p class="text-3xl font-bold text-yellow-600">
            {{ Auth::user()->borrows()->where('status', 'pending')->count() }}
        </p>
    </div>
</div>

<div class="mt-8">
    <h2 class="text-2xl font-semibold mb-4">My Recent Borrows</h2>
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Book</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Borrow Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Return Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach(Auth::user()->borrows()->with('book')->latest()->take(5)->get() as $borrow)
                <tr>
                    <td class="px-6 py-4">{{ $borrow->book->title }}</td>
                    <td class="px-6 py-4">{{ $borrow->borrow_date }}</td>
                    <td class="px-6 py-4">{{ $borrow->return_date }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-sm rounded-full 
                            {{ $borrow->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $borrow->status === 'borrowed' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $borrow->status === 'returned' ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ ucfirst($borrow->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
