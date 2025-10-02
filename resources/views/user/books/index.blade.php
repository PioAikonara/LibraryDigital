@extends('user.layouts.user')

@section('title', 'Browse Books')

@section('user-content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-semibold">Available Books</h1>
    <div class="flex gap-4">
        <select id="category-filter" 
            class="border rounded-lg px-3 py-2"
            value="{{ request('category') }}">
            <option value="">All Categories</option>
            @foreach(\App\Models\Category::all() as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        <input type="text" id="search" 
            placeholder="Search by title or author..." 
            class="border rounded-lg px-3 py-2 w-64"
            value="{{ request('search') }}">
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($books as $book)
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <h3 class="text-xl font-semibold mb-2">{{ $book->title }}</h3>
            <p class="text-gray-600 mb-4">By {{ $book->author }}</p>
            <div class="mb-4">
                <span class="bg-blue-100 text-blue-800 text-sm px-2 py-1 rounded">
                    {{ $book->category->name }}
                </span>
                <span class="ml-2 {{ $book->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $book->stock }} available
                </span>
            </div>
            <p class="text-gray-600 mb-4 line-clamp-3">{{ $book->description }}</p>
            
            @if($book->stock > 0)
            <button onclick="showBorrowModal({{ $book->id }})" 
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Borrow Book
            </button>
            @else
            <button disabled class="bg-gray-300 text-gray-500 px-4 py-2 rounded cursor-not-allowed">
                Out of Stock
            </button>
            @endif
        </div>
    </div>
    @empty
    <div class="col-span-3 text-center text-gray-500 py-8">
        No books found matching your criteria.
    </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $books->appends(request()->query())->links() }}
</div>

<!-- Borrow Modal -->
<div id="borrowModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg max-w-md mx-auto mt-20 p-6">
        <h2 class="text-xl font-semibold mb-4">Borrow Book</h2>        <form action="{{ route('user.borrows.store') }}" method="POST">
            @csrf
            <input type="hidden" name="book_id" id="book_id">
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Borrow Date</label>
                <input type="date" name="borrow_date" required
                    min="{{ date('Y-m-d') }}"
                    value="{{ date('Y-m-d') }}"
                    class="w-full border rounded-lg px-3 py-2">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Return Date</label>
                <input type="date" name="return_date" required
                    min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                    value="{{ date('Y-m-d', strtotime('+7 days')) }}"
                    class="w-full border rounded-lg px-3 py-2">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Notes</label>
                <textarea name="notes" rows="2" 
                    class="w-full border rounded-lg px-3 py-2"></textarea>
            </div>
            
            <div class="flex justify-end gap-3">
                <button type="button" onclick="hideBorrowModal()"
                    class="px-4 py-2 text-gray-600 hover:text-gray-800">
                    Cancel
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Submit Request
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('search').addEventListener('input', debounce(applyFilters, 300));
document.getElementById('category-filter').addEventListener('change', applyFilters);

function applyFilters() {
    const search = document.getElementById('search').value;
    const category = document.getElementById('category-filter').value;
    
    const params = new URLSearchParams(window.location.search);
    
    if (search) params.set('search', search);
    else params.delete('search');
    
    if (category) params.set('category', category);
    else params.delete('category');
    
    window.location.search = params.toString();
}

function showBorrowModal(bookId) {
    document.getElementById('book_id').value = bookId;
    document.getElementById('borrowModal').classList.remove('hidden');
}

function hideBorrowModal() {
    document.getElementById('borrowModal').classList.add('hidden');
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
