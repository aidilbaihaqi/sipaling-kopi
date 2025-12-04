<?php

namespace App\Models;

/**
 * ============================================
 * CATEGORY MODEL
 * ============================================
 * 
 * Model untuk tabel categories
 * Menyimpan kategori menu (Kopi Panas, Kopi Dingin, Non Kopi, Makanan)
 * 
 * Table: categories
 * Columns:
 * - id: bigint (primary key)
 * - name: string (nama kategori)
 * - description: text (deskripsi kategori)
 * - created_at: timestamp
 * - updated_at: timestamp
 * 
 * Relationships:
 * - hasMany: Menu (satu kategori punya banyak menu)
 * 
 * @package  App\Models
 * @version  1.0.0
 */

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * Fillable Attributes
     * 
     * Kolom yang boleh diisi secara mass assignment
     *
     * @var array
     */
    protected $fillable = [
        'name',         // Nama kategori (contoh: "Kopi Panas")
        'description'   // Deskripsi kategori (contoh: "Berbagai macam kopi panas")
    ];

    /**
     * ============================================
     * RELATIONSHIPS
     * ============================================
     */

    /**
     * Relationship: Category has many Menus
     * 
     * Satu kategori bisa memiliki banyak menu
     * Contoh: Kategori "Kopi Panas" punya menu Espresso, Americano, dll
     * 
     * Usage:
     * $category->menus; // Ambil semua menu dalam kategori ini
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    /**
     * ============================================
     * HELPER METHODS
     * ============================================
     */

    /**
     * Get total menu count in this category
     * 
     * @return int
     */
    public function getMenuCount(): int
    {
        return $this->menus()->count();
    }

    /**
     * Get available menus only
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAvailableMenus()
    {
        return $this->menus()->where('is_available', true)->get();
    }
}
