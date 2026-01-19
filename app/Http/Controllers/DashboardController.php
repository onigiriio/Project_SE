<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display user dashboard with membership and borrowing information.
     * 
     * <<includes>> dependencies:
     * - Check Membership Details
     * - Pay Fines (method/view)
     * - View Borrowing History
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->user_type === 'librarian') {
            return view('dashboard-librarian', [
                'user' => $user,
                'totalUsers' => \App\Models\User::where('user_type', 'user')->count(),
                'totalBooks' => \App\Models\Book::count(),
                'activeBorrows' => \App\Models\Borrow::whereNull('returned_at')->count(),
                'totalFines' => \App\Models\Fine::whereIn('status', ['unpaid', 'partial'])->count(),
            ]);
        }

        // User (Patron) Dashboard
        $membershipStatus = $user->getMembershipStatus();
        $borrowingHistory = $user->borrows()
            ->with('book')
            ->latest('borrowed_at')
            ->paginate(10);
        $unpaidFines = $user->unpaidFines()->get();
        $totalOutstandingFines = $user->getTotalOutstandingFines();
        $activeBorrows = $user->borrows()
            ->with('book')
            ->whereNull('returned_at')
            ->get();

        return view('dashboard', [
            'user' => $user,
            'membershipStatus' => $membershipStatus,
            'borrowingHistory' => $borrowingHistory,
            'unpaidFines' => $unpaidFines,
            'totalOutstandingFines' => $totalOutstandingFines,
            'activeBorrows' => $activeBorrows,
        ]);
    }
}
