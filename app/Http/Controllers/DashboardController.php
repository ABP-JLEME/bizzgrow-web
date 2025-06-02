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
            return redirect()->route('login'); // Redirect to login if not authenticated
        }

        $totalPendapatan = 12500000;
        $totalProdukTerjual = 450;


        $totalPendapatanFormatted = 'Rp ' . number_format($totalPendapatan, 0, ',', '.'); // Simple format
        if (extension_loaded('intl')) {
            $formatter = new NumberFormatter('id_ID', NumberFormatter::CURRENCY);
            $totalPendapatanFormatted = $formatter->formatCurrency($totalPendapatan, 'IDR');
        }

        return view('dashboard', compact('totalPendapatan', 'totalProdukTerjual', 'totalPendapatanFormatted'));
    }
}
