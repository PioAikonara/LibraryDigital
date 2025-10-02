<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    /**
     * Display a listing of books for admin.
     */
    public function index(Request $request)
    {
        $query = Book::with('category');

        // Search by title or author
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by stock status
        if ($request->filled('stock')) {
            if ($request->stock === 'in_stock') {
                $query->where('stock', '>', 0);
            } elseif ($request->stock === 'out_of_stock') {
                $query->where('stock', '=', 0);
            }
        }

        $books = $query->latest()->paginate(10);
        $categories = Category::all();
        
        return view('admin.books.index', compact('books', 'categories'));
    }

    /**
     * Display a listing of books for users.
     */
    public function userIndex(Request $request)
    {
        $query = Book::with('category');

        // Books in stock
        $query->where('stock', '>', 0);

        // Search by title or author
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        $books = $query->latest()->paginate(12);
        return view('user.books.index', compact('books'));
    }

    /**
     * Show the form for creating a new book.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.books.create', compact('categories'));
    }

    /**
     * Store a newly created book in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'publication_year' => 'required|digits:4',
            'description' => 'nullable|string',
            'isbn' => 'required|string|unique:books',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id'
        ]);

        Book::create($validated);
        return redirect()->route('admin.books.index')->with('success', 'Book added successfully!');
    }
    public function edit(Book $book)
    {
        $categories = Category::all();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'publication_year' => 'required|digits:4',
            'description' => 'nullable|string',
            'isbn' => 'required|string|unique:books,isbn,' . $book->id,
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id'
        ]);

        $book->update($validated);
        return redirect()->route('admin.books.index')->with('success', 'Book updated successfully!');
    }

    /**
     * Remove the specified book from storage.
     */
    public function destroy(Book $book)
    {
        if ($book->borrows()->where('status', 'borrowed')->exists()) {
            return back()->withErrors(['error' => 'Cannot delete book that is currently borrowed!']);
        }

        $book->delete();
        return redirect()->route('admin.books.index')->with('success', 'Book deleted successfully!');
    }
}
