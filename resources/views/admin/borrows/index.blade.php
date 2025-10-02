@extends('admin.layouts.admin')

@section('title', 'Manage Borrows')

@section('admin-content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold">Manage Borrow Requests</h1>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Book</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Borrow Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Return Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($borrows as $borrow)
            <tr>
                <td class="px-6 py-4">{{ $borrow->user->name }}</td>
                <td class="px-6 py-4">{{ $borrow->book->title }}</td>
                <td class="px-6 py-4">{{ $borrow->borrow_date->format('Y-m-d') }}</td>
                <td class="px-6 py-4">{{ $borrow->return_date->format('Y-m-d') }}</td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-sm rounded-full 
                        {{ $borrow->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $borrow->status === 'borrowed' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $borrow->status === 'returned' ? 'bg-gray-100 text-gray-800' : '' }}
                        {{ $borrow->status === 'overdue' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ ucfirst($borrow->status) }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    @if($borrow->status === 'pending')
                    <form action="{{ route('admin.borrows.approve', $borrow) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-green-600 hover:text-green-800 mr-3">Approve</button>
                        </form>
                    @endif
                    @if($borrow->status === 'borrowed')
                        <form action="{{ route('admin.borrows.return', $borrow) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-blue-600 hover:text-blue-800">Return</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $borrows->links() }}
</div>
@endsection
