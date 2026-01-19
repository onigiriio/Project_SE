<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Handle user registration with library card ID generation and fee tracking.
     * 
     * <<include>> dependencies:
     * - Validates and fills the registration form
     * - Automatically generates a unique 'Library Card ID'
     * - Stubs out a process for 'Pay Registration Fee'
     */
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        // Generate unique library card ID (Format: LIB-YYYYMMDD-XXXXX)
        $libraryCardId = $this->generateLibraryCardId();

        // Create new user
        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'user_type' => $validated['user_type'],
            'membership' => $validated['membership'] === 'yes',
            'library_card_id' => $libraryCardId,
            'registration_fee_paid' => false, // Stub for fee payment
        ]);

        // Log the user in
        Auth::login($user);

        // Redirect to fee payment if membership is selected
        if ($user->membership) {
            return redirect()->route('registration.pay-fee')->with('success', 'Account created successfully! Please complete the registration fee payment.');
        }

        return redirect('/dashboard')->with('success', 'Account created successfully!');
    }

    /**
     * Generate a unique library card ID.
     * Format: LIB-YYYYMMDD-[sequential number]
     */
    private function generateLibraryCardId(): string
    {
        $date = now()->format('Ymd');
        $counter = User::whereDate('created_at', today())->count() + 1;
        $paddedCounter = str_pad($counter, 5, '0', STR_PAD_LEFT);
        
        return "LIB-{$date}-{$paddedCounter}";
    }
}
