@extends('petugas.layouts.petugas')

@section('title', 'Input Peminjaman Baru')

@section('petugas-content')
<div class="bg-gradient-to-r from-cyan-600 to-teal-600 rounded-lg shadow-lg p-6 mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Input Peminjaman Baru</h1>
            <p class="text-white opacity-90">Catat peminjaman buku baru</p>
        </div>
        <a href="{{ route('petugas.borrows.index') }}" 
           class="bg-white text-teal-600 px-6 py-2 rounded-lg hover:bg-teal-50 transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg inline-flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali</span>
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
    <form action="{{ route('petugas.borrows.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2" for="user_id">Peminjam</label>
                <select name="user_id" id="user_id" required 
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="">Pilih Peminjam</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} - {{ $user->email }}
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2" for="book_id">Buku</label>
                <select name="book_id" id="book_id" required 
                        class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="">Pilih Buku</option>
                    @foreach($books as $book)
                        <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                            {{ $book->title }} (Stok: {{ $book->stock }})
                        </option>
                    @endforeach
                </select>
                @error('book_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2" for="borrow_date">Tanggal Pinjam</label>
                <input type="date" name="borrow_date" id="borrow_date" 
                       value="{{ old('borrow_date', date('Y-m-d')) }}" required
                       class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                @error('borrow_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2" for="return_date">Tanggal Kembali</label>
                <input type="date" name="return_date" id="return_date" 
                       value="{{ old('return_date', date('Y-m-d', strtotime('+7 days'))) }}" required
                       class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                @error('return_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2" for="notes">Catatan</label>
                <textarea name="notes" id="notes" rows="3" 
                          class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" 
                    class="bg-gradient-to-r from-cyan-500 to-teal-500 text-white px-6 py-2 rounded-lg hover:from-cyan-600 hover:to-teal-600 transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg inline-flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                </svg>
                <span>Simpan Peminjaman</span>
            </button>
        </div>
    </form>
</div>
@endsection