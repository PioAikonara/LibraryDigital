@extends('petugas.layouts.petugas')

@section('title', 'Manajemen Peminjaman')

@section('petugas-content')
<div class="bg-gradient-to-r from-cyan-600 to-teal-600 rounded-lg shadow-lg p-6 mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">Manajemen Peminjaman</h1>
            <p class="text-white opacity-90">Kelola peminjaman dan pengembalian buku</p>
        </div>
        <a href="{{ route('petugas.borrows.create') }}" 
           class="bg-white text-teal-600 px-6 py-2 rounded-lg hover:bg-teal-50 transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg inline-flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Input Peminjaman Baru</span>
        </a>
    </div>
</div>

<!-- Filter Section -->
<div class="bg-white rounded-lg shadow-lg p-6 mb-6 border border-gray-200">
    <form action="{{ route('petugas.borrows.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select name="status" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="borrowed" {{ request('status') == 'borrowed' ? 'selected' : '' }}>Dipinjam</option>
                <option value="pending_return" {{ request('status') == 'pending_return' ? 'selected' : '' }}>Menunggu Validasi</option>
                <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Dikembalikan</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" 
                   class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" 
                   class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full bg-teal-500 text-white px-4 py-2 rounded-lg hover:bg-teal-600 transition duration-300">
                Filter
            </button>
        </div>
    </form>
</div>

<!-- Borrowing List -->
<div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Peminjam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Buku</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Tgl Pinjam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Tgl Kembali</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Denda</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($borrows as $borrow)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $borrow->user->name }}</div>
                        <div class="text-sm text-gray-500">{{ $borrow->user->email }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $borrow->book->title }}</div>
                        <div class="text-sm text-gray-500">ISBN: {{ $borrow->book->isbn }}</div>
                    </td>
                    <td class="px-6 py-4">{{ $borrow->borrow_date->format('d/m/Y') }}</td>
                    <td class="px-6 py-4">
                        <span class="{{ Carbon\Carbon::parse($borrow->return_date)->isPast() && $borrow->status !== 'returned' ? 'text-red-600 font-medium' : '' }}">
                            {{ $borrow->return_date->format('d/m/Y') }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-sm rounded-full
                            {{ $borrow->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $borrow->status === 'borrowed' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $borrow->status === 'pending_return' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $borrow->status === 'returned' ? 'bg-green-100 text-green-800' : '' }}">
                            {{ ucfirst($borrow->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($borrow->fine_amount > 0)
                            <span class="text-red-600 font-medium">
                                Rp {{ number_format($borrow->fine_amount, 0, ',', '.') }}
                            </span>
                            @if($borrow->fine_paid)
                                <span class="ml-2 px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Lunas</span>
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-3">
                            @if($borrow->status === 'pending')
                            <form action="{{ route('petugas.borrows.approve', $borrow) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-white bg-green-500 hover:bg-green-600 px-3 py-1 rounded-full text-sm flex items-center space-x-1 transition duration-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Setujui</span>
                                </button>
                            </form>
                            @endif

                            @if($borrow->status === 'pending_return')
                            <form action="{{ route('petugas.borrows.validate-return', $borrow) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-white bg-blue-500 hover:bg-blue-600 px-3 py-1 rounded-full text-sm flex items-center space-x-1 transition duration-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Validasi Kembali</span>
                                </button>
                            </form>
                            @endif

                            @if($borrow->fine_amount > 0 && !$borrow->fine_paid)
                            <button onclick="markFinePaid({{ $borrow->id }})" 
                                    class="text-white bg-yellow-500 hover:bg-yellow-600 px-3 py-1 rounded-full text-sm flex items-center space-x-1 transition duration-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Bayar Denda</span>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        Tidak ada data peminjaman
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t">
        {{ $borrows->links() }}
    </div>
</div>

<!-- Script for handling fine payment -->
<script>
function markFinePaid(borrowId) {
    if (confirm('Tandai denda sebagai sudah dibayar?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ url('petugas/borrows') }}/${borrowId}/pay-fine`;
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection