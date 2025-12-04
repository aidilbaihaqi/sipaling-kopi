<?php

namespace App\Models;

/**
 * ============================================
 * ORDER ITEM MODEL (LEGACY/ALTERNATIVE)
 * ============================================
 * 
 * Model alternatif untuk order items
 * Note: Sistem utama menggunakan OrderDetail, tapi OrderItem tetap ada
 * untuk kompatibilitas dengan beberapa fitur
 * 
 * Table: order_items
 * Columns:
 * - id: bigint (primary key)
 * - order_id: bigint (foreign key ke orders)
 * - menu_id: bigint (foreign key ke menus)
 * - quantity: integer (jumlah item)
 * - price: decimal (harga per item)
 * - subtotal: decimal (total harga = price * quantity)
 * - status: string (status item)
 * - created_at: timestamp
 * - updated_at: timestamp
 * 
 * @package  App\Models
 * @version  1.0.0
 */

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * Fillable Attributes
     *
     * @var array
     */
    protected $fillable = [
        'order_id',   // ID order
        'menu_id',    // ID menu
        'quantity',   // Jumlah item
        'price',      // Harga per item
        'subtotal',   // Total harga (price * quantity)
        'status',     // Status item
    ];

    /**
     * Attribute Casting
     *
     * @var array
     */
    protected $casts = [
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * ============================================
     * RELATIONSHIPS
     * ============================================
     */

    /**
     * Relationship: OrderItem belongs to Order
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relationship: OrderItem belongs to Menu
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
     * Update subtotal based on price and quantity
     * Subtotal = price * quantity
     * 
     * @return void
     */
    public function updateSubtotal()
    {
        $this->subtotal = $this->price * $this->quantity;
        $this->save();
    }
}
