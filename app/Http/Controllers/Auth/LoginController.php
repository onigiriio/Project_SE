<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login with robust error handling.
     * 
     * <<extends>> Invalid Username or Password
     * - Enhanced error messages for failed login attempts
     * - Session regeneration for security
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        // Attempt authentication
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        // Authentication failed - provide detailed feedback
        return back()
            ->withErrors([
                'email' => 'The provided email address is not registered, or the password is incorrect. Please try again or register a new account.',
            ])
            ->onlyInput('email')
            ->with('login_attempt_failed', true);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'You have been logged out successfully.');
    }
}
