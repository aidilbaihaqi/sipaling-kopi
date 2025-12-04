<?php

namespace App\Models;

/**
 * ============================================
 * USER MODEL
 * ============================================
 * 
 * Model untuk tabel users
 * Menyimpan data user sistem (admin, cashier, kitchen)
 * 
 * Table: users
 * Columns:
 * - id: bigint (primary key)
 * - name: string (nama user)
 * - email: string (email unique)
 * - password: string (hashed password)
 * - role: enum (admin|cashier|kitchen)
 * - remember_token: string
 * - created_at: timestamp
 * - updated_at: timestamp
 * 
 * @package  App\Models
 * @version  1.0.0
 */

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * Fillable Attributes
     * 
     * Kolom yang boleh diisi secara mass assignment
     * Digunakan saat User::create() atau $user->fill()
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',        // Nama lengkap user
        'email',       // Email user (unique)
        'password',    // Password (akan di-hash otomatis)
        'role',        // Role user: admin, cashier, atau kitchen
    ];

    /**
     * Hidden Attributes
     * 
     * Kolom yang disembunyikan saat serialization (toArray, toJson)
     * Password dan remember_token tidak akan muncul di response API
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',        // Password tidak boleh terlihat di response
        'remember_token',  // Token remember me tidak boleh terlihat
    ];

    /**
     * Attribute Casting
     * 
     * Mengubah tipe data kolom secara otomatis
     * - email_verified_at: diubah ke Carbon datetime
     * - password: otomatis di-hash saat disimpan
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',  // Cast ke Carbon instance
            'password' => 'hashed',              // Auto hash password
        ];
    }

    /**
     * ============================================
     * RELATIONSHIPS
     * ============================================
     */

    /**
     * Relationship: User has many Orders
     * 
     * Satu user (cashier) bisa membuat banyak orders
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * ============================================
     * HELPER METHODS
     * ============================================
     */

    /**
     * Check if user is Admin
     * 
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is Cashier
     * 
     * @return bool
     */
    public function isCashier(): bool
    {
        return $this->role === 'cashier';
    }

    /**
     * Check if user is Kitchen Staff
     * 
     * @return bool
     */
    public function isKitchen(): bool
    {
        return $this->role === 'kitchen';
    }
}
