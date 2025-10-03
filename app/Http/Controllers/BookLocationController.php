<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookLocation;
use Illuminate\Http\Request;

class BookLocationController extends Controller
{
    public function index()
    {
        $books = Book::with('location')
            ->orderBy('title')
            ->paginate(15);
            
        return view('petugas.locations.index', compact('books'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'rack_number' => 'required|string|max:50',
            'shelf_number' => 'required|string|max:50',
            'section' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500'
        ]);

        $book->location()->updateOrCreate(
            ['book_id' => $book->id],
            $validated
        );

        return back()->with('success', 'Lokasi buku berhasil diperbarui.');
    }
}