<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Borrow;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LibrarianController extends Controller
{
    /**
     * Display the librarian dashboard with key statistics.
     */
    public function dashboard(): View
    {
        $totalBooks = \App\Models\Book::count();
        $totalUsers = User::where('user_type', 'user')->count();
        $activeBorrows = Borrow::whereNull('returned_at')->count();
        $premiumMembers = User::where('membership', true)->count();

        return view('librarian.dashboard', compact(
            'totalBooks',
            'totalUsers',
            'activeBorrows',
            'premiumMembers'
        ));
    }

    /**
     * Display all users with their membership status and active borrows.
     */
    public function users(): View
    {
        $users = User::where('user_type', 'user')
            ->withCount('borrows')
            ->with(['borrows' => function ($query) {
                $query->whereNull('returned_at');
            }])
            ->latest()
            ->get();

        $activeBorrows = Borrow::whereNull('returned_at')
            ->with(['user', 'book'])
            ->latest()
            ->get();

        return view('librarian.users', compact('users', 'activeBorrows'));
    }

    /**
     * Get user details for modal display (AJAX endpoint).
     */
    public function userDetails(User $user)
    {
        // Load all borrows for the user
        $user->load(['borrows' => function ($query) {
            $query->with('book')->latest();
        }]);

        return view('librarian.partials.user-details', compact('user'));
    }
}
