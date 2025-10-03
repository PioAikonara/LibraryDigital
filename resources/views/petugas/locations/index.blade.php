@extends('petugas.layouts.petugas')

@section('title', 'Manajemen Lokasi Buku')

@section('petugas-content')
<div class="bg-gradient-to-r from-cyan-600 to-teal-600 rounded-lg shadow-lg p-6 mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Manajemen Lokasi Buku</h1>
            <p class="text-white opacity-90">Kelola lokasi dan penempatan buku</p>
        </div>
    </div>
</div>

<!-- Filter dan Pencarian -->
<div class="bg-white rounded-lg shadow-lg p-6 mb-6 border border-gray-200">
    <div class="flex gap-4">
        <div class="flex-1">
            <input type="text" id="search" placeholder="Cari buku..."
                   class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
        </div>
        <div class="w-48">
            <select id="rack_filter" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="">Semua Rak</option>
                <!-- Add rack options dynamically -->
            </select>
        </div>
    </div>
</div>

<!-- Daftar Buku dan Lokasinya -->
<div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Buku</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Rak</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Shelf</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Bagian</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Catatan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($books as $book)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $book->title }}</div>
                        <div class="text-sm text-gray-500">ISBN: {{ $book->isbn }}</div>
                    </td>
                    <td class="px-6 py-4">{{ $book->location->rack_number ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $book->location->shelf_number ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $book->location->section ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $book->location->notes ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <button onclick="editLocation({{ $book->id }}, {{ json_encode($book->location) }})"
                                class="text-teal-600 hover:text-teal-800 bg-teal-50 px-3 py-1 rounded-full hover:bg-teal-100 transition duration-300 inline-flex items-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span>Edit Lokasi</span>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t">
        {{ $books->links() }}
    </div>
</div>

<!-- Modal Edit Lokasi -->
<div id="locationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
    <div class="min-h-screen px-4 text-center">
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="locationForm" method="POST" class="p-6">
                @csrf
                <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Lokasi Buku</h3>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Rak</label>
                    <input type="text" name="rack_number" id="rack_number" required
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Shelf</label>
                    <input type="text" name="shelf_number" id="shelf_number" required
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bagian</label>
                    <input type="text" name="section" id="section"
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                    <textarea name="notes" id="notes" rows="3"
                              class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"></textarea>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()"
                            class="px-4 py-2 text-gray-700 hover:text-gray-900 bg-gray-100 rounded-lg hover:bg-gray-200 transition duration-300">
                        Batal
                    </button>
                    <button type="submit"
                            class="bg-gradient-to-r from-cyan-500 to-teal-500 text-white px-4 py-2 rounded-lg hover:from-cyan-600 hover:to-teal-600 transition duration-300">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editLocation(bookId, location) {
    const modal = document.getElementById('locationModal');
    const form = document.getElementById('locationForm');
    
    form.action = `{{ url('petugas/book-locations') }}/${bookId}/update`;
    
    if (location) {
        document.getElementById('rack_number').value = location.rack_number || '';
        document.getElementById('shelf_number').value = location.shelf_number || '';
        document.getElementById('section').value = location.section || '';
        document.getElementById('notes').value = location.notes || '';
    } else {
        form.reset();
    }
    
    modal.classList.remove('hidden');
}

function closeModal() {
    document.getElementById('locationModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('locationModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endsection