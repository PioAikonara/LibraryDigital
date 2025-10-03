@extends('admin.layouts.admin')

@section('title', 'Categories Management')

@section('admin-content')
<div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg shadow-lg p-6 mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Categories Management</h1>
            <p class="text-white opacity-90">Organize your book categories</p>
        </div>
        <button onclick="document.getElementById('addCategoryModal').classList.remove('hidden')" 
                class="bg-white text-indigo-600 px-6 py-2 rounded-lg hover:bg-indigo-50 transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add Category</span>
            </div>
        </button>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
    <table class="min-w-full">
        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Books Count</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($categories as $category)
            <tr>
                <td class="px-6 py-4">{{ $category->name }}</td>
                <td class="px-6 py-4">{{ $category->books_count }}</td>
                <td class="px-6 py-4 flex space-x-3">
                    <button onclick="editCategory({{ $category->id }}, '{{ $category->name }}')"
                        class="text-blue-600 hover:text-blue-800 flex items-center space-x-1 bg-blue-50 px-3 py-1 rounded-full hover:bg-blue-100 transition duration-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span>Edit</span>
                    </button>
                    @if($category->books_count === 0)
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline"
                            onsubmit="return confirm('Are you sure you want to delete this category?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 flex items-center space-x-1 bg-red-50 px-3 py-1 rounded-full hover:bg-red-100 transition duration-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span>Delete</span>
                            </button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Add Category Modal -->
<div id="addCategoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
    <div class="relative p-8 bg-white w-96 mx-auto mt-20 rounded-lg shadow-xl transform transition-all">
        <h2 class="text-xl font-bold mb-4">Add New Category</h2>
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2" for="name">Category Name</label>
                <input type="text" name="name" id="name" required
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                    value="{{ old('name') }}">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="document.getElementById('addCategoryModal').classList.add('hidden')"
                    class="px-4 py-2 text-gray-600 hover:text-gray-800 bg-gray-100 rounded-lg hover:bg-gray-200 transition duration-300">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 transition duration-300 transform hover:-translate-y-0.5">
                    Add Category
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="editCategoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
    <div class="relative p-8 bg-white w-96 mx-auto mt-20 rounded-lg">
        <h2 class="text-xl font-bold mb-4">Edit Category</h2>
        <form id="editCategoryForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2" for="edit_name">Category Name</label>
                <input type="text" name="name" id="edit_name" required
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="document.getElementById('editCategoryModal').classList.add('hidden')"
                    class="px-4 py-2 text-gray-600 hover:text-gray-800">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    Update Category
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function editCategory(id, name) {
    document.getElementById('edit_name').value = name;
    document.getElementById('editCategoryForm').action = `{{ route('admin.categories.index') }}/${id}`;x    
    document.getElementById('editCategoryModal').classList.remove('hidden');
}

// Show modals if there are validation errors
@if($errors->any() && old('_method') === 'PUT')
    document.getElementById('editCategoryModal').classList.remove('hidden');
@elseif($errors->any())
    document.getElementById('addCategoryModal').classList.remove('hidden');
@endif
</script>
@endsection
