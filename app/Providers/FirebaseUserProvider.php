<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;
use App\Models\User; // Model User Anda
use Exception;

class FirebaseUserProvider implements UserProvider
{
    protected $auth;

    public function __construct(FirebaseAuth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Retrieve a user by their unique identifier (Firebase UID).
     */
    public function retrieveById($identifier)
    {
        try {
            // Ini akan dipanggil ketika Laravel mencoba me-retrieve user dari sesi
            // ID yang disimpan di sesi adalah UID Firebase.
            $firebaseUserRecord = $this->auth->getUser($identifier);

            return new User([
                'id' => $firebaseUserRecord->uid,
                'email' => $firebaseUserRecord->email,
                'name' => $firebaseUserRecord->displayName,
                'photoUrl' => $firebaseUserRecord->photoUrl,
                'customClaims' => $firebaseUserRecord->customClaims,
            ]);
        } catch (Exception $e) {
            // Jika user tidak ditemukan di Firebase (misal sudah dihapus) atau token kadaluarsa
            // Laravel akan menganggap user tidak valid.
            return null;
        }
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     */
    public function retrieveByToken($identifier, $token)
    {
        // Untuk Firebase Auth, kita tidak menggunakan "remember me" token.
        // Cukup panggil retrieveById.
        return $this->retrieveById($identifier);
    }

    /**
     * Update the "remember me" token for the given user in the storage.
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        // Tidak perlu melakukan apa-apa karena kita tidak menggunakan "remember me" token
        // atau menyimpannya di DB lokal.
    }

    /**
     * Retrieve a user by the given credentials.
     * TIDAK DIGUNAKAN dalam pendekatan Frontend JavaScript.
     * Otentikasi dilakukan oleh JS di frontend.
     */
    public function retrieveByCredentials(array $credentials)
    {
        return null;
    }

    /**
     * Validate a user against the given credentials.
     * TIDAK DIGUNAKAN dalam pendekatan Frontend JavaScript.
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return false;
    }

    /**
     * Rehash the user's password if required.
     */
    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false): void
    {
        // Password dikelola oleh Firebase, jadi tidak ada rehash di sisi Laravel.
    }
}
