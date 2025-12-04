<?php

namespace App\Models;

/**
 * ============================================
 * ORDER DETAIL MODEL
 * ============================================
 * 
 * Model untuk tabel order_details
 * Menyimpan detail item dalam setiap pesanan
 * 
 * Table: order_details
 * Columns:
 * - id: bigint (primary key)
 * - order_id: bigint (foreign key ke orders)
 * - menu_id: bigint (foreign key ke menus)
 * - quantity: integer (jumlah item yang dipesan)
 * - price: decimal (harga per item saat transaksi)
 * - created_at: timestamp
 * - updated_at: timestamp
 * 
 * Relationships:
 * - belongsTo: Order (detail milik satu order)
 * - belongsTo: Menu (detail merujuk ke satu menu)
 * 
 * Note: Harga disimpan di sini untuk history, karena harga menu bisa berubah
 * 
 * @package  App\Models
 * @version  1.0.0
 */

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    /**
     * Guarded Attributes
     * 
     * Semua kolom bisa diisi kecuali 'id'
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Attribute Casting
     * 
     * @var array
     */
    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * ============================================
     * RELATIONSHIPS
     * ============================================
     */

    /**
     * Relationship: OrderDetail belongs to Order
     * 
     * Setiap detail milik satu order
     * 
     * Usage:
     * $detail->order; // Ambil order dari detail ini
     * $detail->order->order_number; // Nomor order
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relationship: OrderDetail belongs to Menu
     * 
     * Setiap detail merujuk ke satu menu
     * Digunakan untuk ambil nama menu, gambar, dll
     * 
     * Usage:
     * $detail->menu; // Ambil menu dari detail ini
     * $detail->menu->name; // Nama menu
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * ============================================
     * HELPER METHODS
     * ============================================
     */

    /**
     * Calculate subtotal for this detail
     * Subtotal = price * quantity
     * 
     * @return float
     */
    public function getSubtotal(): float
    {
        return $this->price * $this->quantity;
    }

    /**
     * Get formatted subtotal in Rupiah
     * 
     * @return string
     */
    public function getFormattedSubtotal(): string
    {
        return 'Rp ' . number_format($this->getSubtotal(), 0, ',', '.');
    }
}