<?php

namespace App\Http\Controllers\Admin;

/**
 * ============================================
 * USER CONTROLLER (ADMIN)
 * ============================================
 * 
 * Controller untuk mengelola user sistem
 * Fitur: CRUD user (admin, cashier, kitchen)
 * 
 * Routes:
 * - GET    /admin/users         -> index   (daftar user)
 * - GET    /admin/users/create  -> create  (form tambah user)
 * - POST   /admin/users         -> store   (simpan user baru)
 * - GET    /admin/users/{id}/edit -> edit  (form edit user)
 * - PUT    /admin/users/{id}    -> update  (update user)
 * - DELETE /admin/users/{id}    -> destroy (hapus user)
 * 
 * @package  App\Http\Controllers\Admin
 * @version  1.0.0
 */

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display list of users
     * 
     * Menampilkan daftar semua user (admin, cashier, kitchen)
     * 
     * Route: GET /admin/users
     * View: resources/views/admin/users/index.blade.php
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil semua user, urutkan dari yang terbaru
        $users = User::latest()->get();
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show create user form
     * 
     * Menampilkan form untuk menambah user baru
     * 
     * Route: GET /admin/users/create
     * View: resources/views/admin/users/create.blade.php
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store new user
     * 
     * Menyimpan user baru ke database
     * Password otomatis di-hash menggunakan bcrypt
     * 
     * Route: POST /admin/users
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',           // Nama wajib diisi
            'email' => 'required|email|unique:users',      // Email wajib unik
            'password' => 'required|min:6',                // Password minimal 6 karakter
            'role' => 'required|in:admin,cashier,kitchen', // Role harus salah satu dari 3 ini
        ]);

        // Hash password sebelum disimpan
        $validated['password'] = Hash::make($validated['password']);
        
        // Simpan user baru
        User::create($validated);
        
        // Redirect dengan pesan sukses
        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil ditambahkan');
    }

    /**
     * Show edit user form
     * 
     * Menampilkan form untuk edit user
     * 
     * Route: GET /admin/users/{id}/edit
     * View: resources/views/admin/users/edit.blade.php
     * 
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update existing user
     * 
     * Mengupdate data user yang sudah ada
     * Password hanya diupdate jika diisi (opsional)
     * 
     * Route: PUT /admin/users/{id}
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        // Validasi input
        // Email harus unik kecuali untuk user ini sendiri
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,cashier,kitchen',
        ]);

        // Jika password diisi, hash dan tambahkan ke validated
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        // Update user
        $user->update($validated);
        
        // Redirect dengan pesan sukses
        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil diupdate');
    }

    /**
     * Delete user
     * 
     * Menghapus user dari database
     * Tidak bisa menghapus akun sendiri (untuk keamanan)
     * 
     * Route: DELETE /admin/users/{id}
     * 
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        // Cek apakah user mencoba menghapus akun sendiri
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Tidak bisa menghapus akun sendiri');
        }
        
        // Hapus user
        $user->delete();
        
        // Redirect dengan pesan sukses
        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil dihapus');
    }
}
