<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class MembershipController extends Controller
{
    /**
     * Handle membership upgrade request
     */
    public function upgrade(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'duration' => 'required|in:1,2,3,6',
        ]);

        // Get the authenticated user
        $user = auth()->user();

        // Update user membership status
        $user->membership = true;
        $user->membership_duration = (int)$validated['duration'];
        
        // Calculate membership expiry date
        $expiryDate = Carbon::now()->addMonths((int)$validated['duration']);
        $user->membership_expiry = $expiryDate;

        $user->save();

        // Redirect back with success message
        return redirect()->back()->with('success', "Membership upgraded successfully! Your membership is valid until " . $expiryDate->format('M d, Y'));
    }
}
