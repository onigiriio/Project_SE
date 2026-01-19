<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;

class RegistrationFeeController extends Controller
{
    /**
     * Show registration fee payment form.
     * 
     * Stub implementation for 'Pay Registration Fee' use case.
     */
    public function show(): View
    {
        $user = auth()->user();

        // Redirect if user has already paid
        if ($user->registration_fee_paid) {
            return redirect()->route('dashboard')
                ->with('info', 'You have already completed your registration fee payment.');
        }

        return view('registration.pay-fee', [
            'user' => $user,
            'fee_amount' => config('library.registration_fee', 10.00),
        ]);
    }

    /**
     * Process registration fee payment.
     * 
     * Stub implementation - In a real system, this would integrate with a payment gateway
     * such as Stripe, PayPal, or Square. For now, it marks the fee as paid.
     */
    public function pay(Request $request)
    {
        $user = auth()->user();

        // Validate payment method
        $validated = $request->validate([
            'payment_method' => ['required', 'in:credit_card,debit_card,bank_transfer'],
            'agree_terms' => ['required', 'accepted'],
        ]);

        // TODO: Integrate actual payment gateway here
        // For now, we'll stub the payment processing
        $paymentProcessed = $this->processPayment($user, config('library.registration_fee', 10.00));

        if ($paymentProcessed) {
            $user->update([
                'registration_fee_paid' => true,
            ]);

            return redirect()->route('dashboard')
                ->with('success', 'Registration fee payment processed successfully! Your library membership is now active.');
        }

        return back()
            ->with('error', 'Payment processing failed. Please try again or contact support.');
    }

    /**
     * Stub method for payment processing.
     * 
     * In production, this should integrate with a real payment provider:
     * - Stripe
     * - PayPal
     * - Square
     * - etc.
     */
    private function processPayment($user, float $amount): bool
    {
        // Stub implementation - always returns true for now
        // In production, this would:
        // 1. Call payment gateway API
        // 2. Handle transaction ID tracking
        // 3. Log payment records
        // 4. Handle refunds if needed

        return true;
    }

    /**
     * Cancel registration fee payment and optionally delete account.
     */
    public function cancel(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'delete_account' => ['nullable', 'boolean'],
        ]);

        if ($validated['delete_account'] ?? false) {
            // Delete user account
            auth()->logout();
            $user->delete();

            return redirect('/login')
                ->with('info', 'Your account has been deleted.');
        }

        // Just redirect to dashboard
        return redirect()->route('dashboard')
            ->with('info', 'You can complete your registration fee payment anytime from your dashboard.');
    }
}
