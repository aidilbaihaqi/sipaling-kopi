<?php

namespace App\Http\Controllers\Kitchen;

/**
 * ============================================
 * KITCHEN CONTROLLER
 * ============================================
 * 
 * Controller untuk mengelola operasi dapur
 * Fitur:
 * - Melihat dan update status pesanan
 * - Mengelola stok menu
 * - Toggle ketersediaan menu
 * 
 * Routes:
 * - GET  /kitchen                          -> index   (daftar pesanan)
 * - POST /kitchen/order/{order}/update-status -> updateStatus (update status pesanan)
 * - GET  /kitchen/stock                    -> stock   (manajemen stok)
 * - POST /kitchen/stock/{menu}/toggle      -> toggleMenuAvailability (toggle available)
 * - POST /kitchen/stock/{menu}/update-stock -> updateStock (update stok)
 * 
 * @package  App\Http\Controllers\Kitchen
 * @version  1.0.0
 */

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Menu;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    /**
     * Display list of orders
     * 
     * Menampilkan daftar pesanan yang perlu diproses dapur
     * Status yang ditampilkan: pending, processing, ready
     * 
     * Route: GET /kitchen
     * View: resources/views/kitchen/index.blade.php
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil pesanan dengan status pending, processing, atau ready
        // Urutkan dari yang terbaru
        $orders = Order::with('items.menu')
            ->whereIn('status', ['pending', 'processing', 'ready'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('kitchen.index', compact('orders'));
    }

    /**
     * Update order status
     * 
     * Mengubah status pesanan:
     * - pending -> processing (mulai diproses)
     * - processing -> ready (siap diambil)
     * - ready -> completed (selesai)
     * - any -> cancelled (dibatalkan)
     * 
     * Saat status diubah ke 'ready', stok menu akan dikurangi
     * 
     * Route: POST /kitchen/order/{order}/update-status
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, Order $order)
    {
        // Validasi input status
        $request->validate([
            'status' => 'required|in:pending,processing,ready,cancelled',
        ]);

        $newStatus = $request->status;

        // ============================================
        // KURANGI STOK SAAT STATUS JADI 'READY'
        // ============================================
        // Jika status berubah ke 'ready', kurangi stok menu
        if ($order->status !== 'ready' && $newStatus === 'ready') {
            // Cek stok dulu sebelum dikurangi
            foreach ($order->items as $item) {
                $menu = $item->menu;
                if ($menu->stock < $item->quantity) {
                    return redirect()->back()
                        ->with('error', "Stok menu '{$menu->name}' tidak cukup untuk menyelesaikan pesanan.");
                }
            }

            // Kurangi stok setiap menu
            foreach ($order->items as $item) {
                $menu = $item->menu;
                $menu->stock = max(0, $menu->stock - $item->quantity);
                
                // Jika stok habis, set is_available = false
                $menu->is_available = $menu->stock > 0;
                $menu->save();
            }
        }

        // Update status order
        $order->status = $newStatus;
        $order->save();

        return redirect()->back()
            ->with('success', 'Status pesanan berhasil diubah!');
    }

    /**
     * Display stock management page
     * 
     * Menampilkan halaman manajemen stok menu
     * Fitur filter:
     * - Search by nama menu
     * - Filter by kategori
     * - Filter by ketersediaan (available/unavailable)
     * 
     * Route: GET /kitchen/stock
     * View: resources/views/kitchen/stock.blade.php
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function stock(Request $request)
    {
        // Ambil semua kategori untuk filter
        $categories = \App\Models\Category::all();
        
        // Query builder untuk menu
        $query = Menu::with('category');

        // ============================================
        // FILTER: SEARCH BY NAME
        // ============================================
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        // ============================================
        // FILTER: BY CATEGORY
        // ============================================
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // ============================================
        // FILTER: BY AVAILABILITY
        // ============================================
        if ($request->filled('availability')) {
            if ($request->availability === 'available') {
                // Menu tersedia: is_available = true DAN stock > 0
                $query->where('is_available', true)->where('stock', '>', 0);
            } elseif ($request->availability === 'unavailable') {
                // Menu tidak tersedia: is_available = false ATAU stock <= 0
                $query->where(function ($q) {
                    $q->where('is_available', false)->orWhere('stock', '<=', 0);
                });
            }
        }

        // Ambil hasil query, urutkan by nama
        $menus = $query->orderBy('name')->get();

        return view('kitchen.stock', [
            'menus' => $menus,
            'categories' => $categories,
            'search' => $request->search ?? '',
            'category' => $request->category ?? '',
            'availability' => $request->availability ?? '',
        ]);
    }

    /**
     * Toggle menu availability
     * 
     * Mengubah status ketersediaan menu (available/unavailable)
     * Tidak bisa set available jika stok habis
     * 
     * Route: POST /kitchen/stock/{menu}/toggle
     * 
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleMenuAvailability(Menu $menu)
    {
        // Cek apakah stok habis
        if ($menu->stock <= 0) {
            return redirect()->back()
                ->with('error', 'Tidak bisa tandai tersedia karena stok habis!');
        }

        // Toggle status is_available
        $menu->is_available = !$menu->is_available;
        $menu->save();

        return redirect()->back()
            ->with('success', 'Status ketersediaan menu berhasil diubah!');
    }

    /**
     * Update menu stock
     * 
     * Mengupdate jumlah stok menu
     * Jika stok > 0, otomatis set is_available = true
     * Jika stok = 0, otomatis set is_available = false
     * 
     * Route: POST /kitchen/stock/{menu}/update-stock
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStock(Request $request, Menu $menu)
    {
        // Validasi input stok
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        // Update stok
        $menu->stock = $request->stock;
        
        // Auto set is_available berdasarkan stok
        $menu->is_available = $menu->stock > 0;
        $menu->save();

        return redirect()->back()
            ->with('success', "Stok menu '{$menu->name}' berhasil diperbarui!");
    }
}
