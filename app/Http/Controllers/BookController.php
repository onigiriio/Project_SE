<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Genre;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookController extends Controller
{
    /**
     * Display the book catalogue.
     */
    public function catalogue(): View
    {
        $trendingBooks = Book::trending();
        $recommendedBooks = Book::recommended();
        $genres = Genre::with('books')->get();

        return view('books.catalogue', [
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

        return view('books.show', [
            'book' => $book,
            'reviews' => $reviews,
        ]);
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
            ->where('user_id', auth()->id())
            ->first();

        if ($existingReview) {
            $existingReview->update([
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? $existingReview->comment,
            ]);
        } else {
            Review::create([
                'book_id' => $book->id,
                'user_id' => auth()->id(),
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
}
