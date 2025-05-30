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
        // Get the authenticated user
        $user = Auth::user();

        // Check if user is authenticated (middleware 'auth' should handle this,
        // but defensive check is good if route is accidentally unprotected)
        if (!$user) {
            return redirect()->route('login'); // Redirect to login if not authenticated
        }

        // Get user data from your custom User model
        $namaUser = $user->name ?? $user->email; // Fallback to email if name is null
        $photoUser = $user->photoUrl ?? null; // Assuming your User model has a photoUrl property

        // --- Dummy Data (Replace with real data from Firestore later) ---
        // For now, let's use some example data as you did in Flutter reference
        $totalPendapatan = 12500000;
        $totalProdukTerjual = 450;

        // Format currency (requires PHP's intl extension to be enabled)
        // If intl is not enabled, you can do a simple string format or format in Blade/JS
        $totalPendapatanFormatted = 'Rp ' . number_format($totalPendapatan, 0, ',', '.'); // Simple format
        if (extension_loaded('intl')) {
            $formatter = new NumberFormatter('id_ID', NumberFormatter::CURRENCY);
            $totalPendapatanFormatted = $formatter->formatCurrency($totalPendapatan, 'IDR');
        }
        // --- END Dummy Data ---


        return view('dashboard', compact('namaUser', 'photoUser', 'totalPendapatan', 'totalProdukTerjual', 'totalPendapatanFormatted'));
    }
}
