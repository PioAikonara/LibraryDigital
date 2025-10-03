@extends('admin.layouts.admin')

@section('title', 'Edit Book')

@section('admin-content')
<div class="max-w-2xl mx-auto">
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow-lg p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">Edit Book</h1>
                <p class="text-white opacity-90">Update book information</p>
            </div>
            <a href="{{ route('admin.books.index') }}" 
               class="bg-white text-indigo-600 px-6 py-2 rounded-lg hover:bg-indigo-50 transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg inline-flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Back to List</span>
            </a>
        </div>
    </div>

    <form action="{{ route('admin.books.update', $book) }}" method="POST" class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-2 gap-6">
            <div class="col-span-2">
                <label class="block text-gray-700 font-medium mb-2" for="title">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $book->title) }}" required
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm hover:border-blue-300 transition duration-300 @error('title') border-red-500 @enderror">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2" for="author">Author</label>
                <input type="text" name="author" id="author" value="{{ old('author', $book->author) }}" required
                    class="w-full px-3 py-2 border rounded-lg @error('author') border-red-500 @enderror">
                @error('author')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2" for="publisher">Publisher</label>
                <input type="text" name="publisher" id="publisher" value="{{ old('publisher', $book->publisher) }}"
                    class="w-full px-3 py-2 border rounded-lg @error('publisher') border-red-500 @enderror">
                @error('publisher')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2" for="isbn">ISBN</label>
                <input type="text" name="isbn" id="isbn" value="{{ old('isbn', $book->isbn) }}" required
                    class="w-full px-3 py-2 border rounded-lg @error('isbn') border-red-500 @enderror">
                @error('isbn')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2" for="publication_year">Publication Year</label>
                <input type="number" name="publication_year" id="publication_year" 
                    value="{{ old('publication_year', $book->publication_year) }}" required min="1900" max="{{ date('Y') }}"
                    class="w-full px-3 py-2 border rounded-lg @error('publication_year') border-red-500 @enderror">
                @error('publication_year')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2" for="category_id">Category</label>
                <select name="category_id" id="category_id" required class="w-full px-3 py-2 border rounded-lg @error('category_id') border-red-500 @enderror">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" 
                            {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2" for="stock">Stock</label>
                <input type="number" name="stock" id="stock" value="{{ old('stock', $book->stock) }}" required min="0"
                    class="w-full px-3 py-2 border rounded-lg @error('stock') border-red-500 @enderror">
                @error('stock')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="col-span-2">
                <label class="block text-gray-700 font-medium mb-2" for="description">Description</label>
                <textarea name="description" id="description" rows="4" 
                    class="w-full px-3 py-2 border rounded-lg @error('description') border-red-500 @enderror">{{ old('description', $book->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.books.index') }}" 
                class="px-4 py-2 text-gray-700 hover:text-gray-900">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 transition duration-300 transform hover:-translate-y-0.5 shadow-lg inline-flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                <span>Update Book</span>
            </button>
        </div>
    </form>
</div>
@endsection
