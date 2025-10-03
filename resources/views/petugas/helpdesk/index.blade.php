@extends('petugas.layouts.petugas')

@section('title', 'Helpdesk')

@section('petugas-content')
<div class="bg-gradient-to-r from-cyan-600 to-teal-600 rounded-lg shadow-lg p-6 mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Helpdesk</h1>
            <p class="text-white opacity-90">Kelola keluhan dan bantuan pengguna</p>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="bg-white rounded-lg shadow-lg p-6 mb-6 border border-gray-200">
    <form action="{{ route('petugas.helpdesk.index') }}" method="GET" class="flex gap-4">
        <div class="flex-1">
            <select name="status" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Sedang Ditangani</option>
                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>
        <button type="submit" class="px-6 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition duration-300">
            Filter
        </button>
    </form>
</div>

<!-- Complaints List -->
<div class="space-y-6">
    @forelse($complaints as $complaint)
    <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
        <div class="p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $complaint->subject }}</h3>
                    <p class="text-sm text-gray-500">Oleh: {{ $complaint->user->name }}</p>
                </div>
                <span class="px-3 py-1 rounded-full text-sm font-semibold
                    {{ $complaint->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $complaint->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}
                    {{ $complaint->status === 'resolved' ? 'bg-green-100 text-green-800' : '' }}">
                    {{ ucfirst($complaint->status) }}
                </span>
            </div>

            <div class="mt-4">
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-gray-700">{{ $complaint->description }}</p>
                </div>

                @if($complaint->response)
                <div class="mt-4 pl-4 border-l-4 border-teal-500">
                    <p class="text-sm text-gray-500">Respon dari: {{ $complaint->handler->name }}</p>
                    <p class="mt-1 text-gray-700">{{ $complaint->response }}</p>
                </div>
                @endif
            </div>

            @if($complaint->status !== 'resolved')
            <div class="mt-6">
                <button onclick="showResponseForm({{ $complaint->id }})"
                        class="bg-gradient-to-r from-cyan-500 to-teal-500 text-white px-4 py-2 rounded-lg hover:from-cyan-600 hover:to-teal-600 transition duration-300 inline-flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                    </svg>
                    <span>Tanggapi</span>
                </button>
            </div>
            @endif
        </div>
    </div>
    @empty
    <div class="bg-white rounded-lg shadow-md p-6 text-center">
        <p class="text-gray-500">Tidak ada keluhan yang perlu ditangani.</p>
    </div>
    @endforelse

    <div class="mt-6">
        {{ $complaints->links() }}
    </div>
</div>

<!-- Response Modal -->
<div id="responseModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
    <div class="min-h-screen px-4 text-center">
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="responseForm" method="POST" class="p-6">
                @csrf
                <h3 class="text-lg font-medium text-gray-900 mb-4">Tanggapi Keluhan</h3>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Respon</label>
                    <textarea name="response" rows="4" required
                              class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"></textarea>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeResponseModal()"
                            class="px-4 py-2 text-gray-700 hover:text-gray-900 bg-gray-100 rounded-lg hover:bg-gray-200 transition duration-300">
                        Batal
                    </button>
                    <button type="submit"
                            class="bg-gradient-to-r from-cyan-500 to-teal-500 text-white px-4 py-2 rounded-lg hover:from-cyan-600 hover:to-teal-600 transition duration-300">
                        Kirim Respon
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showResponseForm(complaintId) {
    const modal = document.getElementById('responseModal');
    const form = document.getElementById('responseForm');
    
    form.action = `{{ url('petugas/helpdesk/complaints') }}/${complaintId}/respond`;
    modal.classList.remove('hidden');
}

function closeResponseModal() {
    document.getElementById('responseModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('responseModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeResponseModal();
    }
});
</script>
@endsection