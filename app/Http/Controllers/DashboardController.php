<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->user_type === 'librarian') {
            return view('dashboard-librarian');
        }

        return view('dashboard');
    }

    public function profile()
    {
        $user = auth()->user();
        $borrowHistory = $user->borrows()
            ->with('book')
            ->latest()
            ->paginate(10);

        return view('profile', [
            'user' => $user,
            'borrowHistory' => $borrowHistory,
        ]);
    }
}
