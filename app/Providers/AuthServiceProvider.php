<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Providers\FirebaseUserProvider; // Import custom provider Anda
use Kreait\Firebase\Contract\Auth as FirebaseAuth; // Import kontrak Auth Firebase

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        //
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Auth::provider('firebase', function ($app, array $config) {
            $firebaseAuth = $app->make(\Kreait\Firebase\Contract\Auth::class);
            return new \App\Providers\FirebaseUserProvider($firebaseAuth);
        });

        // Daftarkan driver otentikasi 'firebase'
        Auth::extend('firebase', function ($app, $name, array $config) {
            // Dapatkan instance Firebase Auth dari Service Container
            $firebaseAuth = $app->make(FirebaseAuth::class);

            // Buat instance dari user provider kustom Anda
            $userProvider = new FirebaseUserProvider($firebaseAuth);

            // Kembalikan instance dari SessionGuard, karena kita tetap pakai sesi Laravel
            return new \Illuminate\Auth\SessionGuard($name, $userProvider, $app['session.store']);
        });
    }
}
