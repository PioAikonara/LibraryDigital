<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Book;
use App\Models\Borrow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowController extends Controller
{
    /**
     * Display a listing of all borrows for admin.
     */
    public function index()
    {
        $borrows = Borrow::with(['user', 'book', 'book.category'])->latest()->paginate(10);
        return view('admin.borrows.index', compact('borrows'));
    }

    /**
     * Store a newly created borrow request in storage.
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'borrow_date' => 'required|date|after_or_equal:today',
            'return_date' => [
                'required',
                'date',
                'after:borrow_date',
                function ($attribute, $value, $fail) use ($request) {
                    $borrowDate = Carbon::parse($request->borrow_date);
                    $returnDate = Carbon::parse($value);

                    if ($returnDate->gt($borrowDate->copy()->addDays(30))) {
                        $fail('Tanggal pengembalian tidak boleh lebih dari 30 hari setelah tanggal peminjaman.');
                    }
                },
            ],
            'notes' => 'nullable|string|max:500'
        ]);

        $book = Book::findOrFail($validated['book_id']);

        if ($book->stock < 1) {
            return back()->withErrors(['error' => 'Maaf, stok buku tidak tersedia!']);
        }

        $borrow = Borrow::create([
            'user_id' => Auth::id(),
            'book_id' => $validated['book_id'],
            'borrow_date' => $validated['borrow_date'],
            'return_date' => $validated['return_date'],
            'notes' => $validated['notes'],
            'status' => 'pending'
        ]);

        return redirect()->route('user.borrows')->with('success', 'Permintaan peminjaman buku berhasil diajukan!');
    }

    /**
     * Approve a pending borrow request.
     */
    public function approve(Borrow $borrow)
    {
        if ($borrow->status !== 'pending') {
            return back()->withErrors(['error' => 'Hanya bisa menyetujui permintaan yang masih pending!']);
        }

        $book = $borrow->book;
        if ($book->stock < 1) {
            return back()->withErrors(['error' => 'Maaf, stok buku tidak tersedia!']);
        }

        // Check if user has any overdue books
        if ($borrow->user->borrows()->where('status', 'overdue')->exists()) {
            return back()->withErrors(['error' => 'Pengguna masih memiliki buku yang terlambat dikembalikan!']);
        }

        $borrow->update([
            'status' => 'borrowed',
            'approved_at' => now()
        ]);
        $book->decrement('stock');

        return back()->with('success', 'Permintaan peminjaman disetujui!');
    }

    /**
     * Return a borrowed book.
     */
    public function return(Borrow $borrow)
    {
        if ($borrow->status !== 'borrowed' && $borrow->status !== 'overdue') {
            return back()->withErrors(['error' => 'Status peminjaman tidak valid untuk pengembalian!']);
        }

        // Hanya user yang meminjam yang bisa mengembalikan buku
        if ($borrow->user_id !== Auth::id()) {
            return back()->withErrors(['error' => 'Anda hanya bisa mengembalikan buku yang Anda pinjam!']);
        }

        $borrow->update([
            'status' => 'pending_return',
            'return_requested_at' => now()
        ]);

        return back()->with('success', 'Permintaan pengembalian buku telah diajukan dan menunggu validasi petugas.');
    }

    /**
     * Validate book return by staff.
     */
    public function validateReturn(Borrow $borrow)
    {
        if ($borrow->status !== 'pending_return') {
            return back()->withErrors(['error' => 'Status peminjaman tidak valid untuk validasi pengembalian!']);
        }

        // Hitung denda jika terlambat
        $fine = $this->calculateFine($borrow);
        
        $borrow->update([
            'status' => 'returned',
            'actual_return_date' => now(),
            'validated_by' => Auth::id(),
            'fine_amount' => $fine
        ]);

        $borrow->book->increment('stock');

        $message = 'Pengembalian buku berhasil divalidasi!';
        if ($fine > 0) {
            $message .= ' Denda keterlambatan: Rp ' . number_format($fine, 0, ',', '.');
        }

        return back()->with('success', $message);
    }

    /**
     * Calculate late return fine
     */
    private function calculateFine(Borrow $borrow)
    {
        if (!$borrow->return_date || !$borrow->actual_return_date) {
            return 0;
        }

        $dueDate = Carbon::parse($borrow->return_date);
        $returnDate = Carbon::parse($borrow->actual_return_date);

        if ($returnDate <= $dueDate) {
            return 0;
        }

        $daysLate = $returnDate->diffInDays($dueDate);
        $finePerDay = 1000; // Rp 1.000 per hari

        return $daysLate * $finePerDay;
    }

    /**
     * Create borrow by staff
     */
    public function createByStaff()
    {
        $users = User::where('role', 'user')->get();
        $books = Book::where('stock', '>', 0)->get();
        
        return view('petugas.borrows.create', compact('users', 'books'));
    }

    /**
     * Store borrow by staff
     */
    public function storeByStaff(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'borrow_date' => 'required|date|after_or_equal:today',
            'return_date' => [
                'required',
                'date',
                'after:borrow_date',
                function ($attribute, $value, $fail) use ($request) {
                    $borrowDate = Carbon::parse($request->borrow_date);
                    $returnDate = Carbon::parse($value);

                    if ($returnDate->gt($borrowDate->copy()->addDays(30))) {
                        $fail('Maksimal peminjaman adalah 30 hari.');
                    }
                },
            ],
            'notes' => 'nullable|string|max:500'
        ]);

        $book = Book::findOrFail($validated['book_id']);

        if ($book->stock < 1) {
            return back()->withErrors(['error' => 'Stok buku tidak tersedia!']);
        }

        // Check if user has any overdue books
        $user = User::find($validated['user_id']);
        if ($user->borrows()->where('status', 'overdue')->exists()) {
            return back()->withErrors(['error' => 'User masih memiliki buku yang terlambat dikembalikan!']);
        }

        $borrow = Borrow::create([
            'user_id' => $validated['user_id'],
            'book_id' => $validated['book_id'],
            'borrow_date' => $validated['borrow_date'],
            'return_date' => $validated['return_date'],
            'notes' => $validated['notes'],
            'status' => 'borrowed', // Langsung borrowed karena diinput petugas
            'approved_at' => now()
        ]);

        $book->decrement('stock');

        return redirect()->route('petugas.borrows.index')
            ->with('success', 'Peminjaman buku berhasil dicatat!');
    }

    /**
     * Generate and display reports
     */
    public function reports(Request $request)
    {
        $query = Borrow::with(['user', 'book']);

        // Filter by date range
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $borrows = $query->latest()->paginate(15);

        return view('petugas.borrows.reports', compact('borrows'));
    }

    /**
     * Print report
     */
    public function printReport(Request $request)
    {
        $query = Borrow::with(['user', 'book']);

        // Filter by date range
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $borrows = $query->latest()->get();
        
        return view('petugas.borrows.print', compact('borrows'));
    }

    /**
     * Display a listing of the user's borrows.
     */
    public function userBorrows()
    {
        $borrows = Borrow::where('user_id', Auth::id())
            ->with(['book', 'book.category'])
            ->latest()
            ->paginate(10);

        return view('user.borrows.index', compact('borrows'));
    }
}
