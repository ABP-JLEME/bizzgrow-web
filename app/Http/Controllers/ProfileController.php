<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use NumberFormatter;

class ProfileController extends Controller
{
    /**
     * Display the user's profile page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showProfile()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Check if user is authenticated (defensive check)
        if (!$user) {
            return redirect()->route('login');
        }

        // Get user data
        $namaUser = $user->name ?? $user->email; // Fallback to email if name is null
        $emailUser = $user->email;
        $photoUser = $user->photoUrl ?? null; // Assuming User model has photoUrl property
        
        // Format join date in Indonesian
        // $joinDate = 'Bergabung sejak ' . Carbon::parse($user->created_at)->locale('id')->isoFormat('D MMMM YYYY');

        // Get user preferences (with defaults if not set)
        $preferences = [
            'notifications' => $user->email_notifications ?? true,
            'language' => $user->language ?? 'id',
            'theme' => $user->theme ?? 'dark',
        ];

        // App version info
        $appVersion = '1.0.0';

        return view('profile', compact(
            'namaUser', 
            'emailUser', 
            'photoUser', 
            'preferences', 
            'appVersion'
        ));
    }

    /**
     * Show the form for editing the user's profile.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function editProfile()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->photoUrl && Storage::exists('public/avatars/' . basename($user->photoUrl))) {
                Storage::delete('public/avatars/' . basename($user->photoUrl));
            }
            
            $avatarName = time() . '.' . $request->avatar->extension();
            $request->avatar->storeAs('public/avatars', $avatarName);
            $user->photoUrl = asset('storage/avatars/' . $avatarName);
        }

        // Update user information
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone ?? $user->phone,
            'photoUrl' => $user->photoUrl,
        ]);

        return redirect()->route('profile.show')
                        ->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Handle notification settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Support\Renderable
     */
    public function handleNotifications(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }
        
        if ($request->isMethod('post')) {
            $request->validate([
                'email_notifications' => ['boolean'],
                'push_notifications' => ['boolean'],
                'sms_notifications' => ['boolean'],
            ]);

            $user->update([
                'email_notifications' => $request->has('email_notifications'),
                'push_notifications' => $request->has('push_notifications'),
                'sms_notifications' => $request->has('sms_notifications'),
            ]);

            return redirect()->route('profile.notifications')
                            ->with('success', 'Pengaturan notifikasi berhasil diperbarui!');
        }

        return view('profile.notifications', compact('user'));
    }

    /**
     * Handle language settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Support\Renderable
     */
    public function handleLanguage(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $availableLanguages = [
            'id' => 'Bahasa Indonesia',
            'en' => 'English',
            'ms' => 'Bahasa Melayu',
        ];
        
        if ($request->isMethod('post')) {
            $request->validate([
                'language' => ['required', 'in:id,en,ms'],
            ]);

            $user->update([
                'language' => $request->language,
            ]);

            // Set application locale
            app()->setLocale($request->language);
            session(['locale' => $request->language]);

            return redirect()->route('profile.language')
                            ->with('success', 'Bahasa berhasil diubah!');
        }

        return view('profile.language', compact('user', 'availableLanguages'));
    }

    /**
     * Handle theme settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Support\Renderable
     */
    public function handleTheme(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $availableThemes = [
            'light' => 'Terang',
            'dark' => 'Gelap',
            'auto' => 'Otomatis',
        ];
        
        if ($request->isMethod('post')) {
            $request->validate([
                'theme' => ['required', 'in:light,dark,auto'],
            ]);

            $user->update([
                'theme' => $request->theme,
            ]);

            return redirect()->route('profile.theme')
                            ->with('success', 'Tema berhasil diubah!');
        }

        return view('profile.theme', compact('user', 'availableThemes'));
    }

    /**
     * Show help center.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showHelpCenter()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $faqs = [
            [
                'question' => 'Bagaimana cara mengubah profil saya?',
                'answer' => 'Anda dapat mengubah profil dengan mengklik foto profil di halaman Akun, kemudian pilih "Edit Profil".'
            ],
            [
                'question' => 'Bagaimana cara mengubah password?',
                'answer' => 'Masuk ke halaman Akun > Edit Profil > Ubah Password, kemudian masukkan password lama dan password baru.'
            ],
            [
                'question' => 'Bagaimana cara mengatur notifikasi?',
                'answer' => 'Pergi ke halaman Akun > Preferensi > Notifikasi untuk mengatur jenis notifikasi yang ingin Anda terima.'
            ],
            [
                'question' => 'Bagaimana cara melihat laporan penjualan?',
                'answer' => 'Anda dapat melihat laporan penjualan di halaman Dashboard atau menu Penjualan.'
            ],
        ];

        return view('profile.help-center', compact('faqs'));
    }

    /**
     * Show terms of service.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showTermsOfService()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        return view('profile.terms-of-service');
    }

    /**
     * Show privacy policy.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showPrivacyPolicy()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        return view('profile.privacy-policy');
    }

    /**
     * Show app version information.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showAppVersion()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $appInfo = [
            'version' => '1.0.0',
            'build' => '20250602001',
            'release_date' => '2 Juni 2025',
            'developer' => 'Your Company Name',
            'last_updated' => Carbon::now()->locale('id')->isoFormat('D MMMM YYYY'),
        ];

        return view('profile.app-version', compact('appInfo'));
    }

    /**
     * Handle user logout.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleLogout(Request $request)
    {
        // Update last login time before logout
        $user = Auth::user();
        if ($user) {
            $user->update(['last_login_at' => now()]);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah berhasil keluar dari akun.');
    }

    /**
     * Delete user avatar.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAvatar()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }
        
        if ($user->photoUrl && Storage::exists('public/avatars/' . basename($user->photoUrl))) {
            Storage::delete('public/avatars/' . basename($user->photoUrl));
        }
        
        $user->update(['photoUrl' => null]);
        
        return redirect()->route('profile.edit')
                        ->with('success', 'Foto profil berhasil dihapus!');
    }

    /**
     * Update user password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.show')
                        ->with('success', 'Password berhasil diperbarui!');
    }

    /**
     * Get user statistics for profile dashboard.
     *
     * @return array
     */
    public function getUserStatistics()
    {
        $user = Auth::user();

        if (!$user) {
            return [];
        }
        
        // --- Dummy Data (Replace with real data from database later) ---
        $totalOrders = 25; // Replace with $user->orders()->count();
        $totalSpent = 5750000; // Replace with $user->orders()->sum('total_amount');
        
        // Format currency using NumberFormatter if available
        $totalSpentFormatted = 'Rp ' . number_format($totalSpent, 0, ',', '.'); // Simple format
        if (extension_loaded('intl')) {
            $formatter = new NumberFormatter('id_ID', NumberFormatter::CURRENCY);
            $totalSpentFormatted = $formatter->formatCurrency($totalSpent, 'IDR');
        }
        // --- END Dummy Data ---
        
        return [
            'total_orders' => $totalOrders,
            'total_spent' => $totalSpent,
            'total_spent_formatted' => $totalSpentFormatted,
            'member_since' => Carbon::parse($user->created_at)->diffForHumans(),
            'last_login' => $user->last_login_at ? Carbon::parse($user->last_login_at)->diffForHumans() : 'Belum pernah login',
        ];
    }
}