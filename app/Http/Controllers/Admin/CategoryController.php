<?php

namespace App\Http\Controllers\Admin;

/**
 * ============================================
 * CATEGORY CONTROLLER (ADMIN)
 * ============================================
 * 
 * Controller untuk mengelola kategori menu
 * Fitur: CRUD kategori (Kopi Panas, Kopi Dingin, Non Kopi, Makanan)
 * 
 * Routes:
 * - GET    /admin/categories        -> index   (daftar kategori)
 * - POST   /admin/categories        -> store   (simpan kategori baru)
 * - PUT    /admin/categories/{id}   -> update  (update kategori)
 * - DELETE /admin/categories/{id}   -> destroy (hapus kategori)
 * 
 * @package  App\Http\Controllers\Admin
 * @version  1.0.0
 */

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display list of categories
     * 
     * Menampilkan daftar semua kategori beserta jumlah menu di tiap kategori
     * 
     * Route: GET /admin/categories
     * View: resources/views/admin/categories/index.blade.php
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil semua kategori dengan hitung jumlah menu
        // withCount('menus') akan menambahkan kolom 'menus_count'
        $categories = Category::withCount('menus')->get();
        
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Store new category
     * 
     * Menyimpan kategori baru ke database
     * 
     * Route: POST /admin/categories
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',        // Nama kategori wajib diisi
            'description' => 'nullable|string',         // Deskripsi opsional
        ]);

        // Simpan kategori baru
        Category::create($validated);
        
        // Redirect dengan pesan sukses
        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    /**
     * Update existing category
     * 
     * Mengupdate data kategori yang sudah ada
     * 
     * Route: PUT /admin/categories/{id}
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Category $category)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Update kategori
        $category->update($validated);
        
        // Redirect dengan pesan sukses
        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil diupdate');
    }

    /**
     * Delete category
     * 
     * Menghapus kategori dari database
     * Warning: Jika kategori punya menu, menu-menu tersebut juga akan terhapus (cascade)
     * 
     * Route: DELETE /admin/categories/{id}
     * 
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Category $category)
    {
        // Hapus kategori
        $category->delete();
        
        // Redirect dengan pesan sukses
        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus');
    }
}
