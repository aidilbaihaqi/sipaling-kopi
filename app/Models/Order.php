<?php

namespace App\Models;

/**
 * ============================================
 * ORDER MODEL
 * ============================================
 * 
 * Model untuk tabel orders
 * Menyimpan data pesanan/transaksi dari kasir
 * 
 * Table: orders
 * Columns:
 * - id: bigint (primary key)
 * - order_number: string (nomor order unik, contoh: ORD-1764686553)
 * - customer_name: string (nama pelanggan)
 * - user_id: bigint (foreign key ke users - kasir yang input)
 * - total_amount: decimal (total harga pesanan)
 * - payment_amount: decimal (jumlah uang yang dibayar)
 * - payment_method: enum (cash|qris|debit|credit)
 * - status: enum (pending|processing|ready|completed|cancelled)
 * - created_at: timestamp (waktu order dibuat)
 * - updated_at: timestamp
 * 
 * Relationships:
 * - belongsTo: User (order dibuat oleh kasir)
 * - hasMany: OrderDetail (order punya banyak item)
 * 
 * @package  App\Models
 * @version  1.0.0
 */

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * Guarded Attributes
     * 
     * Semua kolom bisa diisi kecuali 'id'
     * Lebih fleksibel daripada $fillable
     *
     * @var array
     */
    protected $guarded = ['id']; 

    /**
     * ============================================
     * RELATIONSHIPS
     * ============================================
     */

    /**
     * Relationship: Order has many OrderDetails
     * 
     * Satu order punya banyak item/detail pesanan
     * Contoh: Order #123 punya 2 Espresso, 1 Cappuccino
     * 
     * Usage:
     * $order->details; // Ambil semua item dalam order ini
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    /**
     * Relationship Alias: Order belongs to Cashier
     * 
     * Alias untuk relasi ke User (kasir yang membuat order)
     * Digunakan di halaman KASIR (print struk)
     * 
     * Usage:
     * $order->cashier->name; // Nama kasir yang input
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cashier()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship Alias: Order belongs to User
     * 
     * Alias untuk relasi ke User (kasir yang membuat order)
     * Digunakan di halaman ADMIN (Dashboard & Laporan)
     * 
     * Usage:
     * $order->user->name; // Nama kasir yang input
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship Alias: Order has many Items
     * 
     * Alias untuk relasi details()
     * Digunakan di Admin Dashboard untuk konsistensi penamaan
     * 
     * Usage:
     * $order->items; // Sama dengan $order->details
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->details();
    }

    /**
     * ============================================
     * HELPER METHODS
     * ============================================
     */

    /**
     * Get change amount (kembalian)
     * 
     * @return float
     */
    public function getChangeAmount(): float
    {
        return $this->payment_amount - $this->total_amount;
    }

    /**
     * Check if order is completed
     * 
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if order is pending
     * 
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Get status badge color for UI
     * 
     * @return string
     */
    public function getStatusColor(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'processing' => 'info',
            'ready' => 'primary',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary'
        };
    }
}