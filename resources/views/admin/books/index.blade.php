@extends('admin.layouts.admin')

@section('title', 'Books Management')

@section('admin-content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold">Books Management</h1>
    <a href="{{ route('admin.books.create') }}" 
        class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
        Add New Book
    </a>
</div>

<!-- Filters -->
<div class="mb-6 flex flex-wrap gap-4">
    <div class="flex-1 min-w-[200px]">
        <input type="text" id="search" placeholder="Search by title or author..."
            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500"
            value="{{ request('search') }}">
    </div>
    <div class="w-48">
        <select id="category_filter" 
            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500">
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

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full">
        <thead class="bg-gray-50">
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
                <td class="px-6 py-4 flex space-x-2">
                    <a href="{{ route('admin.books.edit', $book) }}" 
                        class="text-blue-600 hover:text-blue-800">
                        Edit
                    </a>
                    <form action="{{ route('admin.books.destroy', $book) }}" method="POST" class="inline"
                        onsubmit="return confirm('Are you sure you want to delete this book? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
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
