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
}
