<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->user_type === 'librarian') {
            return redirect()->route('librarian.dashboard');
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

    public function borrows()
    {
        $user = auth()->user();
        $borrowHistory = $user->borrows()
            ->with('book')
            ->latest()
            ->paginate(10);

        return view('borrows', [
            'user' => $user,
            'borrowHistory' => $borrowHistory,
        ]);
    }

    public function updateProfile(\Illuminate\Http\Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update fields (preserve unchanged values by using inputs with defaults)
        $user->username = $validated['username'] ?? $user->username;
        $user->email = $validated['email'] ?? $user->email;

        if (!empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // delete old avatar if exists
            if ($user->avatar) {
                \Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Profile updated successfully!');
    }
}

