<?php

namespace App\Models;

/**
 * ============================================
 * MENU MODEL
 * ============================================
 * 
 * Model untuk tabel menus
 * Menyimpan data menu/produk yang dijual di cafe
 * 
 * Table: menus
 * Columns:
 * - id: bigint (primary key)
 * - category_id: bigint (foreign key ke categories)
 * - name: string (nama menu)
 * - description: text (deskripsi menu)
 * - price: decimal (harga menu)
 * - stock: integer (jumlah stok)
 * - is_available: boolean (status ketersediaan)
 * - image: string (path gambar menu)
 * - created_at: timestamp
 * - updated_at: timestamp
 * 
 * Relationships:
 * - belongsTo: Category (menu milik satu kategori)
 * - hasMany: OrderItem (menu bisa ada di banyak order)
 * 
 * @package  App\Models
 * @version  1.0.0
 */

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    /**
     * Fillable Attributes
     * 
     * Kolom yang boleh diisi secara mass assignment
     *
     * @var array
     */
    protected $fillable = [
        'category_id',   // ID kategori menu
        'name',          // Nama menu (contoh: "Espresso")
        'description',   // Deskripsi menu (contoh: "Kopi hitam pekat")
        'price',         // Harga menu (contoh: 15000.00)
        'stock',         // Jumlah stok tersedia
        'is_available',  // Status tersedia (true/false)
        'image',         // Path gambar menu
    ];

    /**
     * Attribute Casting
     * 
     * Mengubah tipe data kolom secara otomatis
     *
     * @var array
     */
    protected $casts = [
        'price' => 'decimal:2',      // Cast ke decimal dengan 2 angka di belakang koma
        'is_available' => 'boolean', // Cast ke boolean (true/false)
    ];

    /**
     * ============================================
     * RELATIONSHIPS
     * ============================================
     */

    /**
     * Relationship: Menu belongs to Category
     * 
     * Setiap menu milik satu kategori
     * Contoh: Menu "Espresso" milik kategori "Kopi Panas"
     * 
     * Usage:
     * $menu->category; // Ambil kategori dari menu ini
     * $menu->category->name; // Ambil nama kategori
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relationship: Menu has many OrderItems
     * 
     * Satu menu bisa dipesan berkali-kali di berbagai order
     * 
     * Usage:
     * $menu->orderItems; // Ambil semua order yang punya menu ini
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * ============================================
     * HELPER METHODS
     * ============================================
     */

    /**
     * Check if menu is out of stock
     * 
     * @return bool
     */
    public function isOutOfStock(): bool
    {
        return $this->stock <= 0;
    }

    /**
     * Check if menu is available for order
     * Menu available jika: is_available = true DAN stock > 0
     * 
     * @return bool
     */
    public function isAvailableForOrder(): bool
    {
        return $this->is_available && !$this->isOutOfStock();
    }

    /**
     * Reduce stock when ordered
     * 
     * @param int $quantity
     * @return bool
     */
    public function reduceStock(int $quantity): bool
    {
        if ($this->stock >= $quantity) {
            $this->stock -= $quantity;
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Add stock
     * 
     * @param int $quantity
     * @return void
     */
    public function addStock(int $quantity): void
    {
        $this->stock += $quantity;
        $this->save();
    }

    /**
     * Format price to Rupiah
     * 
     * @return string
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}
