<?php

return [
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'firebase_users', // Gunakan provider kustom Anda
        ],
    ],

    'providers' => [
        // Hapus atau komentari bagian 'users' yang menggunakan 'eloquent'
        // 'users' => [
        //     'driver' => 'eloquent',
        //     'model' => App\Models\User::class,
        // ],

        // Tambahkan provider kustom Anda
        'firebase_users' => [ // Beri nama unik untuk provider ini
            'driver' => 'firebase', // Nama driver kustom yang kita buat di AuthServiceProvider
            'model' => App\Models\User::class, // Model User yang Anda modifikasi
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users', // Ini bisa tetap 'users' atau ganti ke 'firebase_users' jika Anda ingin mengelola reset password Firebase
            'table' => 'password_reset_tokens', // Ini tidak akan digunakan jika tidak pakai DB
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];
