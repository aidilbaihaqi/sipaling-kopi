<?php

namespace App\Http\Controllers\Admin;

/**
 * ============================================
 * MENU CONTROLLER (ADMIN)
 * ============================================
 * 
 * Controller untuk mengelola menu/produk cafe
 * Fitur: CRUD menu (Create, Read, Update, Delete)
 * 
 * Routes:
 * - GET    /admin/menus         -> index   (daftar menu)
 * - GET    /admin/menus/create  -> create  (form tambah menu)
 * - POST   /admin/menus         -> store   (simpan menu baru)
 * - GET    /admin/menus/{id}/edit -> edit  (form edit menu)
 * - PUT    /admin/menus/{id}    -> update  (update menu)
 * - DELETE /admin/menus/{id}    -> destroy (hapus menu)
 * 
 * @package  App\Http\Controllers\Admin
 * @version  1.0.0
 */

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display list of menus
     * 
     * Menampilkan daftar semua menu beserta kategorinya
     * 
     * Route: GET /admin/menus
     * View: resources/views/admin/menus/index.blade.php
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil semua kategori untuk filter
        $categories = Category::all();
        
        // Ambil semua menu dengan relasi kategori
        // Urutkan dari yang terbaru
        $menus = Menu::with('category')->latest()->get();
        
        return view('admin.menus.index', compact('menus', 'categories'));
    }

    /**
     * Show create menu form
     * 
     * Menampilkan form untuk menambah menu baru
     * 
     * Route: GET /admin/menus/create
     * View: resources/views/admin/menus/create.blade.php
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Ambil semua kategori untuk dropdown
        $categories = Category::all();
        
        return view('admin.menus.create', compact('categories'));
    }

    /**
     * Store new menu
     * 
     * Menyimpan menu baru ke database
     * 
     * Route: POST /admin/menus
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',  // Kategori harus ada di database
            'name' => 'required|string|max:255',               // Nama menu wajib diisi
            'description' => 'nullable|string',                // Deskripsi opsional
            'price' => 'required|numeric|min:0',               // Harga wajib, minimal 0
            'stock' => 'required|integer|min:0',               // Stok wajib, minimal 0
            'is_available' => 'boolean',                       // Status ketersediaan (true/false)
        ]);

        // Simpan menu baru
        Menu::create($validated);
        
        // Redirect dengan pesan sukses
        return redirect()->route('menus.index')
            ->with('success', 'Menu berhasil ditambahkan');
    }

    /**
     * Show edit menu form
     * 
     * Menampilkan form untuk edit menu
     * 
     * Route: GET /admin/menus/{id}/edit
     * View: resources/views/admin/menus/edit.blade.php
     * 
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\View\View
     */
    public function edit(Menu $menu)
    {
        // Ambil semua kategori untuk dropdown
        $categories = Category::all();
        
        return view('admin.menus.edit', compact('menu', 'categories'));
    }

    /**
     * Update existing menu
     * 
     * Mengupdate data menu yang sudah ada
     * 
     * Route: PUT /admin/menus/{id}
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Menu $menu)
    {
        // Validasi input
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_available' => 'boolean',
        ]);

        // Update menu
        $menu->update($validated);
        
        // Redirect dengan pesan sukses
        return redirect()->route('menus.index')
            ->with('success', 'Menu berhasil diupdate');
    }

    /**
     * Delete menu
     * 
     * Menghapus menu dari database
     * Warning: Jika menu ada di order, bisa menyebabkan error
     * 
     * Route: DELETE /admin/menus/{id}
     * 
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Menu $menu)
    {
        // Hapus menu
        $menu->delete();
        
        // Redirect dengan pesan sukses
        return redirect()->route('menus.index')
            ->with('success', 'Menu berhasil dihapus');
    }
}
