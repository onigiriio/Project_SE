<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\RegisterRequest;
use Illuminate\View\View;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    /**
     * Librarian: Display all users.
     * 
     * <<include>> 'Search/Update Database'
     */
    public function index(Request $request): View
    {
        $this->authorize('manage-users');

        $query = User::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('library_card_id', 'like', "%{$search}%");
            });
        }

        // Filter by user type
        if ($request->filled('user_type') && $request->input('user_type') !== 'all') {
            $query->where('user_type', $request->input('user_type'));
        }

        // Filter by membership status
        if ($request->filled('membership') && $request->input('membership') !== 'all') {
            $membership = $request->input('membership') === 'active';
            $query->where('membership', $membership)
                  ->where('registration_fee_paid', $membership);
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return view('users.index', [
            'users' => $users,
            'search' => $request->input('search', ''),
            'userTypeFilter' => $request->input('user_type', 'all'),
            'membershipFilter' => $request->input('membership', 'all'),
            'totalUsers' => User::count(),
            'activeMembers' => User::where('membership', true)->where('registration_fee_paid', true)->count(),
        ]);
    }

    /**
     * Librarian: Show user details.
     */
    public function show(User $user): View
    {
        $this->authorize('manage-users');

        $borrowHistory = $user->borrows()
            ->with('book')
            ->latest('borrowed_at')
            ->paginate(10);

        $activeFines = $user->unpaidFines()->get();
        $totalFines = $user->getTotalOutstandingFines();

        return view('users.show', [
            'user' => $user,
            'borrowHistory' => $borrowHistory,
            'activeFines' => $activeFines,
            'totalFines' => $totalFines,
            'membershipStatus' => $user->getMembershipStatus(),
        ]);
    }

    /**
     * Librarian: Show form to add new user.
     * 
     * <<include>> 'Add New User'
     */
    public function create(): View
    {
        $this->authorize('manage-users');

        return view('users.create');
    }

    /**
     * Librarian: Store new user.
     */
    public function store(RegisterRequest $request)
    {
        $this->authorize('manage-users');

        $validated = $request->validated();

        // Generate library card ID
        $libraryCardId = $this->generateLibraryCardId();

        User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'user_type' => $validated['user_type'],
            'membership' => $validated['membership'] === 'yes',
            'library_card_id' => $libraryCardId,
            'registration_fee_paid' => $validated['membership'] === 'yes', // Librarian can mark as paid
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User has been created successfully.');
    }

    /**
     * Librarian: Show form to edit user.
     * 
     * <<include>> 'Update User Record'
     */
    public function edit(User $user): View
    {
        $this->authorize('manage-users');

        return view('users.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Librarian: Update user record.
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('manage-users');

        $validated = $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:50', 'unique:users,username,' . $user->id],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'user_type' => ['required', 'in:user,librarian'],
            'membership' => ['required', 'boolean'],
            'registration_fee_paid' => ['required', 'boolean'],
        ]);

        $user->update([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'user_type' => $validated['user_type'],
            'membership' => $validated['membership'],
            'registration_fee_paid' => $validated['registration_fee_paid'],
        ]);

        return redirect()->route('users.show', $user)
            ->with('success', 'User record has been updated successfully.');
    }

    /**
     * Librarian: Delete a user.
     */
    public function destroy(User $user)
    {
        $this->authorize('manage-users');

        if ($user->user_type === 'librarian' && User::where('user_type', 'librarian')->count() <= 1) {
            return back()->with('error', 'Cannot delete the last librarian account.');
        }

        $username = $user->username;
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', "User '{$username}' has been deleted successfully.");
    }

    /**
     * Generate a unique library card ID.
     */
    private function generateLibraryCardId(): string
    {
        $date = now()->format('Ymd');
        $counter = User::whereDate('created_at', today())->count() + 1;
        $paddedCounter = str_pad($counter, 5, '0', STR_PAD_LEFT);
        
        return "LIB-{$date}-{$paddedCounter}";
    }
}
