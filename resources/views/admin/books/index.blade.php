@extends('admin.layouts.admin')

@section('title', 'Books Management')

@section('admin-content')
<div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow-lg p-6 mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Books Management</h1>
            <p class="text-white opacity-90">Manage your library collection</p>
        </div>
        <a href="{{ route('admin.books.create') }}" 
            class="bg-white text-indigo-600 px-6 py-2 rounded-lg hover:bg-indigo-50 transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add New Book</span>
            </div>
        </a>
    </div>
</div>

<!-- Filters -->
<div class="mb-6 flex flex-wrap gap-4">
    <div class="flex-1 min-w-[200px]">
        <input type="text" id="search" placeholder="Search by title or author..."
            class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm hover:border-blue-300 transition duration-300"
            value="{{ request('search') }}">
    </div>
    <div class="w-48">
        <select id="category_filter" 
            class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm hover:border-blue-300 transition duration-300">
            <option value="">All Categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="w-48">
        <select id="stock_filter" 
            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500">
            <option value="">All Stock Status</option>
            <option value="in_stock" {{ request('stock') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
            <option value="out_of_stock" {{ request('stock') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
        </select>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
    <table class="min-w-full">
        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Author</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Publisher</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($books as $book)
            <tr>
                <td class="px-6 py-4">
                    <div class="font-medium text-gray-900">{{ $book->title }}</div>
                    <div class="text-sm text-gray-500">ISBN: {{ $book->isbn }}</div>
                </td>
                <td class="px-6 py-4">{{ $book->author }}</td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-sm rounded-full bg-blue-100 text-blue-800">
                        {{ $book->category->name }}
                    </span>
                </td>
                <td class="px-6 py-4">{{ $book->publisher ?? '-' }}</td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-sm rounded-full {{ $book->stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $book->stock }} copies
                    </span>
                </td>
                <td class="px-6 py-4 flex space-x-3">
                    <a href="{{ route('admin.books.edit', $book) }}" 
                        class="text-blue-600 hover:text-blue-800 flex items-center space-x-1 bg-blue-50 px-3 py-1 rounded-full hover:bg-blue-100 transition duration-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span>Edit</span>
                    </a>
                    <form action="{{ route('admin.books.destroy', $book) }}" method="POST" class="inline"
                        onsubmit="return confirm('Are you sure you want to delete this book? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800 flex items-center space-x-1 bg-red-50 px-3 py-1 rounded-full hover:bg-red-100 transition duration-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <span>Delete</span>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                    No books found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t">
        {{ $books->appends(request()->query())->links() }}
    </div>
</div>

<script>
document.getElementById('search').addEventListener('input', debounce(applyFilters, 300));
document.getElementById('category_filter').addEventListener('change', applyFilters);
document.getElementById('stock_filter').addEventListener('change', applyFilters);

function applyFilters() {
    const search = document.getElementById('search').value;
    const category = document.getElementById('category_filter').value;
    const stock = document.getElementById('stock_filter').value;
    
    const params = new URLSearchParams(window.location.search);
    
    if (search) params.set('search', search);
    else params.delete('search');
    
    if (category) params.set('category', category);
    else params.delete('category');
    
    if (stock) params.set('stock', stock);
    else params.delete('stock');
    
    window.location.search = params.toString();
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}
</script>
@endsection
