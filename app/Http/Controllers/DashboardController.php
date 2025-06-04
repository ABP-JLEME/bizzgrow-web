<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import Auth facade
// Use the NumberFormatter for currency if 'intl' extension is enabled in PHP
use NumberFormatter;

class DashboardController extends Controller
{
    public function showDashboard()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        return view('dashboard');
    }
}
