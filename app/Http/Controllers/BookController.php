<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\Genre;
use App\Models\Review;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Display the book catalogue with search functionality.
     * 
     * <<include>> 'Search Book'
     */
    public function catalogue(Request $request): View
    {
        $q = $request->query('q', '');
        $books = Book::query()
            ->search($q)
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $trendingBooks = Book::trending();
        $recommendedBooks = Book::recommended();
        $genres = Genre::with('books')->get();

        return view('books.catalogue', [
            'books' => $books,
            'q' => $q,
            'trendingBooks' => $trendingBooks,
            'recommendedBooks' => $recommendedBooks,
            'genres' => $genres,
        ]);
    }

    /**
     * Display a specific book's details.
     */
    public function show(Book $book): View
    {
        // Increment view count
        $book->incrementViewCount();

        // Get reviews with user information
        $reviews = $book->reviews()
            ->with('user')
            ->latest()
            ->paginate(5);

        $borrowed = false;
        if (Auth::check()) {
            $borrowed = $book->borrows()
                ->where('user_id', Auth::id())
                ->whereNull('returned_at')
                ->exists();
        }

        return view('books.show', [
            'book' => $book,
            'reviews' => $reviews,
            'borrowed' => $borrowed,
        ]);
    }

    /**
     * Borrow a book for the authenticated user.
     * 
     * <<include>> 'Borrow Book'
     */
    public function borrow(Book $book)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check if user is an active member
        $membershipStatus = Auth::user()->getMembershipStatus();
        if (!$membershipStatus['active']) {
            return redirect()->back()
                ->with('error', 'You must complete your membership registration and fee payment to borrow books.');
        }

        // Check if user already has this book borrowed
        $existing = $book->borrows()
            ->where('user_id', Auth::id())
            ->whereNull('returned_at')
            ->first();

        if ($existing) {
            return redirect()->back()
                ->with('info', 'You have already borrowed this book. Please return it before borrowing again.');
        }

        Borrow::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'borrowed_at' => now(),
            'status' => 'borrowed',
        ]);

        return redirect()->back()
            ->with('success', 'Book borrowed successfully! You can view it in your dashboard.');
    }

    /**
     * Return a borrowed book.
     * 
     * <<include>> 'Return Book' (connects to Catalog view)
     */
    public function returnBook(Borrow $borrow)
    {
        // Ensure user can only return their own books
        if ($borrow->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if already returned
        if ($borrow->returned_at) {
            return redirect()->back()
                ->with('info', 'This book has already been returned.');
        }

        $borrow->update([
            'returned_at' => now(),
            'status' => 'returned',
        ]);

        return redirect()->back()
            ->with('success', 'Book returned successfully. Thank you!');
    }

    /**
     * Get books by genre.
     */
    public function byGenre(Genre $genre): View
    {
        $books = $genre->books()
            ->orderBy('rating', 'desc')
            ->paginate(12);

        return view('books.genre', [
            'genre' => $genre,
            'books' => $books,
        ]);
    }

    /**
     * Store a review for a book.
     */
    public function storeReview(Request $request, Book $book)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Check if user already reviewed this book
        $existingReview = Review::where('book_id', $book->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReview) {
            $existingReview->update([
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? $existingReview->comment,
            ]);
        } else {
            Review::create([
                'book_id' => $book->id,
                'user_id' => Auth::id(),
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
            ]);
        }

        // Update book rating
        $this->updateBookRating($book);

        return redirect()->back()->with('success', 'Review submitted successfully!');
    }

    /**
     * Update the book's average rating.
     */
    private function updateBookRating(Book $book)
    {
        $reviews = $book->reviews;
        
        if ($reviews->isNotEmpty()) {
            $book->rating = $reviews->avg('rating');
            $book->rating_count = $reviews->count();
            $book->save();
        }
    }

    /**
     * Search for books and show the catalogue view.
     */
    public function index(Request $request)
    {
        $q = $request->query('q', '');
        $books = Book::query()
            ->search($q)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $trendingBooks = Book::trending();
        $recommendedBooks = Book::recommended();
        $genres = Genre::with('books')->get();

        return view('books.catalogue', [
            'books' => $books,
            'q' => $q,
            'trendingBooks' => $trendingBooks,
            'recommendedBooks' => $recommendedBooks,
            'genres' => $genres,
        ]);
    }

    /**
     * Show the form for creating a new book.
     * 
     * Librarian only - <<include>> 'Add New Book'
     */
    public function create()
    {
        $genres = Genre::all();
        return view('books.create', compact('genres'));
    }

    /**
     * Store a newly created book.
     * 
     * <<include>> 'Update Record ID' - generates proper database record with auto-increment ID
     */
    public function store(StoreBookRequest $request)
    {
        $validated = $request->validated();

        $book = Book::create($validated);

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $imagePath = $request->file('cover_image')->store('book-covers', 'public');
            $book->update(['cover_image' => $imagePath]);
        }

        // Attach genres
        if ($request->has('genres')) {
            $book->genres()->attach($validated['genres']);
        }

        return redirect()->route('books.show', $book)
            ->with('success', 'Book created successfully! Record ID: ' . $book->id);
    }

    /**
     * Show the form for editing a book.
     * 
     * Librarian only - <<include>> 'Update Book Record'
     */
    public function edit(Book $book)
    {
        $genres = Genre::all();
        return view('books.edit', compact('book', 'genres'));
    }

    /**
     * Update the specified book.
     * 
     * <<include>> 'Update Record ID' - updates record with ID persistence
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $validated = $request->validated();

        $book->update($validated);

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            // Delete old image if exists
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $imagePath = $request->file('cover_image')->store('book-covers', 'public');
            $book->update(['cover_image' => $imagePath]);
        }

        // Sync genres
        if ($request->has('genres')) {
            $book->genres()->sync($validated['genres'] ?? []);
        }

        return redirect()->route('books.show', $book)
            ->with('success', 'Book updated successfully! Record ID: ' . $book->id);
    }

    /**
     * Remove the specified book.
     * 
     * Librarian only - <<include>> 'Delete Book Record'
     */
    public function destroy(Book $book)
    {
        // Delete cover image if exists
        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $bookId = $book->id;
        $book->delete();

        return redirect()->route('books.index')
            ->with('success', "Book (ID: {$bookId}) has been deleted successfully.");
    }
}
