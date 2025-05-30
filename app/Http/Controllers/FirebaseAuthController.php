<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Contract\Auth as FirebaseAuth; // Admin SDK untuk verifikasi
use Kreait\Firebase\Exception\AuthException;
use Kreait\Firebase\Exception\FirebaseException;
use App\Models\User; // Model User Anda

class FirebaseAuthController extends Controller
{
    protected $auth;

    public function __construct(FirebaseAuth $auth)
    {
        $this->auth = $auth;
    }

    // --- Menampilkan Form Login (Tidak ada PHP login logic) ---
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // --- Menampilkan Form Register (Tidak ada PHP register logic) ---
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // --- Endpoint API untuk Menerima & Memverifikasi ID Token dari Frontend ---
    public function verifyFirebaseToken(Request $request)
    {
        $request->validate([
            'idToken' => 'required|string', // Frontend akan mengirim ID Token
        ]);

        $idToken = $request->input('idToken');

        try {
            // Verifikasi ID Token menggunakan Firebase Admin SDK
            $verifiedIdToken = $this->auth->verifyIdToken($idToken);

            $uid = $verifiedIdToken->claims()->get('sub'); // UID pengguna dari token
            $email = $verifiedIdToken->claims()->get('email');
            $displayName = $verifiedIdToken->claims()->get('name');
            $photoUrl = $verifiedIdToken->claims()->get('picture'); // Jika ada di token

            // Buat instance dari model User kita (tanpa DB relasional)
            $user = new User([
                'id' => $uid,
                'email' => $email,
                'name' => $displayName,
                'photoUrl' => $photoUrl,
                'customClaims' => $verifiedIdToken->claims()->all(), // Simpan semua claims jika perlu
            ]);

            // Login user ke sesi Laravel
            Auth::login($user);

            // Respon sukses ke frontend (misalnya, redirect URL)
            return response()->json(['success' => true, 'redirect' => route('dashboard')]);
        } catch (AuthException $e) {
            // Tangani error verifikasi token
            return response()->json(['success' => false, 'message' => 'Token tidak valid atau kadaluarsa.'], 401);
        } catch (FirebaseException $e) {
            return response()->json(['success' => false, 'message' => 'Kesalahan Firebase di backend: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Kesalahan server tidak terduga: ' . $e->getMessage()], 500);
        }
    }

    // --- Logout ---
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
