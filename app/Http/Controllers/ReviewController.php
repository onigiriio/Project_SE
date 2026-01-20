<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Mark a review as helpful
     */
    public function markHelpful(Review $review): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Increment helpful count
        $review->increment('helpful_count');

        return response()->json([
            'success' => true,
            'helpful_count' => $review->helpful_count,
            'message' => 'Thanks for marking this review as helpful!'
        ]);
    }

    /**
     * Delete a review (librarian only)
     */
    public function delete(Review $review): JsonResponse
    {
        // Check if user is a librarian
        if (!Auth::check() || Auth::user()->user_type !== 'librarian') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $book_id = $review->book_id;
        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully!',
            'book_id' => $book_id
        ]);
    }
}
