<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class User implements Authenticatable
{
    use AuthenticatableTrait;

    public $id; // Akan menyimpan Firebase UID
    public $email;
    public $name;
    public $photoUrl; // Tambahkan ini jika Anda ingin menyimpan URL foto profil
    public $customClaims; // Untuk menyimpan custom claims dari Firebase token

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'email',
        'name',
        'photoUrl',
        'customClaims'
    ];

    public function __construct(array $attributes = [])
    {
        // Set attributes from constructor
        foreach ($attributes as $key => $value) {
            $this->{$key} = $value;
        }
    }

    /**
     * Get the name of the unique identifier for the user.
     */
    public function getAuthIdentifierName()
    {
        return 'id'; // Firebase UID akan jadi identifier utama kita
    }

    /**
     * Get the unique identifier for the user.
     */
    public function getAuthIdentifier()
    {
        return $this->id;
    }

    /**
     * Get the password for the user.
     * (Tidak digunakan karena Firebase mengelola password)
     */
    public function getAuthPassword()
    {
        return '';
    }

    /**
     * Get the "remember me" token value.
     */
    public function getRememberToken()
    {
        return null; // Kita tidak menggunakan "remember me" token
    }

    /**
     * Set the "remember me" token value.
     */
    public function setRememberToken($value)
    {
        // Do nothing
    }

    /**
     * Get the "remember me" token name.
     */
    public function getRememberTokenName()
    {
        return ''; // Do nothing
    }

    // Anda bisa tambahkan metode lain jika perlu
}
