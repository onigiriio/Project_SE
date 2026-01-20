<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'username' => ['required', 'string', 'min:3', 'max:50', 'unique:users,username'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'user_type' => ['required', 'in:user,librarian'],
            'membership' => ['required_if:user_type,user', 'nullable', 'in:yes,no'],
            'membership_duration' => ['nullable', 'required_if:membership,yes', 'in:1,2,3,6'],
        ], [
            'email.unique' => 'This email is already registered.',
            'username.unique' => 'This username is already taken.',
            'password.confirmed' => 'Passwords do not match.',
            'password.min' => 'Password must be at least 8 characters.',
            'membership_duration.required_if' => 'Please select a membership duration.',
        ]);

        // Prepare user data
        $userData = [
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'user_type' => $validated['user_type'],
            'membership' => ($validated['membership'] ?? 'no') === 'yes',
        ];

        // If membership is selected, add duration and expiry date
        if (($validated['membership'] ?? null) === 'yes') {
            $duration = (int)$validated['membership_duration'];
            $userData['membership_duration'] = $duration;
            $userData['membership_expiry'] = Carbon::now()->addMonths($duration);
        }

        // Create new user
        $user = User::create($userData);

        // Log the user in
        Auth::login($user);

        return redirect('/dashboard')->with('success', 'Account created successfully!');
    }
}
