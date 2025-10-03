@extends('user.layouts.user')

@section('title', 'My Borrowed Books')

@section('user-content')
<div class="container mx-auto py-6">
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-lg p-6 mb-6">
        <h1 class="text-3xl font-bold text-white mb-2">My Borrowed Books</h1>
        <p class="text-white opacity-90">Manage your borrowed books and returns</p>
    </div>

    @if(session('success'))
    <div class="bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
        <p class="font-bold">Success!</p>
        <p>{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
        <p class="font-bold">Error!</p>
        <p>{{ session('error') }}</p>
    </div>
    @endif

    <div class="grid gap-6 mb-8">
        @forelse($borrows as $borrow)
        <div class="bg-white rounded-lg shadow-lg overflow-hidden transform transition duration-300 hover:scale-102 hover:shadow-xl border-t-4 {{ 
            $borrow->status === 'returned' ? 'border-green-500' :
            ($borrow->status === 'overdue' ? 'border-red-500' :
            ($borrow->status === 'borrowed' ? 'border-blue-500' : 'border-yellow-500'))
        }}">
            <div class="p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $borrow->book->title }}</h3>
                        <p class="text-gray-600 mb-1">Author: {{ $borrow->book->author }}</p>
                        <p class="text-gray-600 mb-1">ISBN: {{ $borrow->book->isbn }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold {{ 
                        $borrow->status === 'returned' ? 'bg-green-100 text-green-800' :
                        ($borrow->status === 'overdue' ? 'bg-red-100 text-red-800' :
                        ($borrow->status === 'borrowed' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800'))
                    }}">
                        {{ ucfirst($borrow->status) }}
                    </span>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-600">Borrowed Date:</p>
                        <p class="font-semibold">{{ $borrow->borrow_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Due Date:</p>
                        <p class="font-semibold {{ Carbon\Carbon::parse($borrow->return_date)->isPast() && $borrow->status !== 'returned' ? 'text-red-600' : '' }}">
                            {{ $borrow->return_date->format('M d, Y') }}
                        </p>
                    </div>
                    @if($borrow->actual_return_date)
                    <div>
                        <p class="text-gray-600">Returned Date:</p>
                        <p class="font-semibold">{{ $borrow->actual_return_date->format('M d, Y') }}</p>
                    </div>
                    @endif
                </div>

                @if($borrow->status === 'borrowed')
                <div class="mt-6">
                    <form action="{{ route('user.borrows.return', $borrow) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-4 py-2 rounded-lg hover:from-green-600 hover:to-emerald-700 transition duration-300 ease-in-out transform hover:-translate-y-1">
                            Return Book
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <p class="text-gray-600">You haven't borrowed any books yet.</p>
            <a href="{{ route('user.books.index') }}" class="mt-4 inline-block bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-2 rounded-lg hover:from-blue-600 hover:to-purple-700 transition duration-300 ease-in-out transform hover:-translate-y-1">
                Browse Books
            </a>
        </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $borrows->links() }}
    </div>
</div>
@endsection
