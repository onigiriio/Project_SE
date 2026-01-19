<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FineController extends Controller
{
    /**
     * Display the current user's fines.
     */
    public function index(): View
    {
        $user = auth()->user();
        $unpaidFines = $user->unpaidFines()->get();
        $totalOutstanding = $user->getTotalOutstandingFines();
        $paidFines = $user->fines()->where('status', 'paid')->get();

        return view('fines.index', [
            'unpaidFines' => $unpaidFines,
            'paidFines' => $paidFines,
            'totalOutstanding' => $totalOutstanding,
        ]);
    }

    /**
     * Show fine payment form.
     */
    public function show(Fine $fine): View
    {
        // Ensure user can only see their own fines
        if ($fine->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('fines.show', [
            'fine' => $fine,
            'remainingBalance' => $fine->getRemainingBalance(),
        ]);
    }

    /**
     * Record a payment towards a fine.
     */
    public function payFine(Request $request, Fine $fine)
    {
        // Ensure user can only pay their own fines
        if ($fine->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01', 'max:' . $fine->getRemainingBalance()],
        ], [
            'amount.required' => 'Payment amount is required.',
            'amount.numeric' => 'Payment amount must be a valid number.',
            'amount.min' => 'Payment amount must be at least 0.01.',
            'amount.max' => 'Payment amount cannot exceed the outstanding balance of ' . $fine->getRemainingBalance(),
        ]);

        // Record the payment
        $fine->recordPayment((float) $validated['amount']);

        return redirect()->route('fines.index')
            ->with('success', 'Payment of $' . number_format($validated['amount'], 2) . ' has been recorded successfully.');
    }

    /**
     * Librarian: View all fines in the system.
     */
    public function all(): View
    {
        $this->authorize('view-all-fines');

        $fines = Fine::with('user', 'borrow')
            ->latest()
            ->paginate(20);

        $unpaidCount = Fine::whereIn('status', ['unpaid', 'partial'])->count();
        $totalUnpaid = Fine::whereIn('status', ['unpaid', 'partial'])->sum('amount');

        return view('fines.all', [
            'fines' => $fines,
            'unpaidCount' => $unpaidCount,
            'totalUnpaid' => $totalUnpaid,
        ]);
    }

    /**
     * Librarian: Create a fine for a user.
     */
    public function create(): View
    {
        $this->authorize('create-fine');

        $users = User::where('user_type', 'user')->get();

        return view('fines.create', [
            'users' => $users,
        ]);
    }

    /**
     * Librarian: Store a new fine.
     */
    public function store(Request $request)
    {
        $this->authorize('create-fine');

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'borrow_id' => ['nullable', 'exists:borrows,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'reason' => ['required', 'string', 'max:255'],
            'due_date' => ['nullable', 'date'],
        ]);

        Fine::create($validated);

        return redirect()->route('fines.all')
            ->with('success', 'Fine has been created successfully.');
    }

    /**
     * Librarian: Edit a fine.
     */
    public function edit(Fine $fine): View
    {
        $this->authorize('update-fine');

        return view('fines.edit', [
            'fine' => $fine,
        ]);
    }

    /**
     * Librarian: Update a fine.
     */
    public function update(Request $request, Fine $fine)
    {
        $this->authorize('update-fine');

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'reason' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:unpaid,partial,paid'],
            'due_date' => ['nullable', 'date'],
        ]);

        $fine->update($validated);

        return redirect()->route('fines.all')
            ->with('success', 'Fine has been updated successfully.');
    }

    /**
     * Librarian: Delete a fine.
     */
    public function destroy(Fine $fine)
    {
        $this->authorize('delete-fine');

        $fine->delete();

        return redirect()->route('fines.all')
            ->with('success', 'Fine has been deleted successfully.');
    }
}
