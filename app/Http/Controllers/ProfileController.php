<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function showProfile()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Check if user is authenticated (defensive check)
        if (!$user) {
            return redirect()->route('login');
        }

        return view('profile');
    }
}
