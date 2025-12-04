<?php

namespace App\Http\Controllers\Admin;

/**
 * ============================================
 * DASHBOARD CONTROLLER (ADMIN)
 * ============================================
 * 
 * Controller untuk menampilkan dashboard admin
 * Menampilkan statistik dan data penting:
 * - Total penjualan hari ini
 * - Total pesanan hari ini
 * - Menu terlaris
 * - Stok kritis (< 30)
 * - Pesanan terbaru
 * - Grafik penjualan 7 hari terakhir
 * 
 * Route: GET /admin/dashboard
 * View: resources/views/admin/dashboard.blade.php
 * 
 * @package  App\Http\Controllers\Admin
 * @version  1.0.0
 */

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard
     * 
     * Menampilkan dashboard dengan berbagai statistik dan data
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(\Illuminate\Http\Request $request)
    {
        // ============================================
        // FILTER RENTANG TANGGAL UNTUK GRAFIK
        // ============================================
        // Default: 7 hari terakhir
        // User bisa pilih: 7 hari, 14 hari, 30 hari, atau custom
        $chartDays = $request->input('chart_days', 7);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Jika ada custom date range
        if ($startDate && $endDate) {
            $chartStartDate = \Carbon\Carbon::parse($startDate)->startOfDay();
            $chartEndDate = \Carbon\Carbon::parse($endDate)->endOfDay();
        } else {
            // Gunakan preset (7, 14, 30 hari)
            $chartStartDate = now()->subDays($chartDays)->startOfDay();
            $chartEndDate = now()->endOfDay();
        }

        // ============================================
        // 1. TOTAL PENJUALAN HARI INI
        // ============================================
        // Hitung total penjualan dari order yang completed hari ini
        $todaySales = Order::whereDate('created_at', today())
            ->where('status', 'completed')
            ->sum('total_amount');

        // ============================================
        // 2. TOTAL PESANAN HARI INI
        // ============================================
        // Hitung jumlah order hari ini (semua status)
        $todayOrders = Order::whereDate('created_at', today())->count();

        // ============================================
        // 3. MENU TERLARIS
        // ============================================
        // Ambil menu dengan total penjualan tertinggi
        // Join order_details dengan menus, lalu sum quantity
        $topMenu = DB::table('order_details')
            ->join('menus', 'order_details.menu_id', '=', 'menus.id')
            ->select('menus.name', DB::raw('SUM(order_details.quantity) as total_sold'))
            ->groupBy('menus.id', 'menus.name')
            ->orderByDesc('total_sold')
            ->first();

        // ============================================
        // 4. STOK KRITIS
        // ============================================
        // Ambil menu dengan stok < 30 dan masih tersedia
        // Untuk alert admin agar segera restock
        $criticalStock = Menu::where('stock', '<', 30)
            ->where('is_available', true)
            ->get();

        // ============================================
        // 5. PESANAN TERBARU
        // ============================================
        // Ambil 10 pesanan terakhir dengan relasi user dan items
        $recentOrders = Order::with('user', 'items.menu')
            ->latest()
            ->take(10)
            ->get();

        // ============================================
        // 6. GRAFIK PENJUALAN DENGAN RENTANG TANGGAL
        // ============================================
        // Data untuk chart penjualan per hari sesuai filter
        // Ambil semua status kecuali cancelled
        $salesData = Order::whereIn('status', ['completed', 'ready', 'processing', 'pending'])
            ->whereBetween('created_at', [$chartStartDate, $chartEndDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date'); // Key by date untuk lookup cepat

        // Generate semua tanggal dalam rentang (termasuk yang tidak ada transaksi)
        $salesChart = collect();
        $currentDate = $chartStartDate->copy();
        
        while ($currentDate <= $chartEndDate) {
            $dateString = $currentDate->format('Y-m-d');
            
            // Cek apakah tanggal ini ada transaksi
            if (isset($salesData[$dateString])) {
                $salesChart->push([
                    'date' => $dateString,
                    'total' => $salesData[$dateString]->total
                ]);
            } else {
                // Tidak ada transaksi, set total = 0
                $salesChart->push([
                    'date' => $dateString,
                    'total' => 0
                ]);
            }
            
            $currentDate->addDay();
        }

        // Return view dengan semua data
        return view('admin.dashboard', compact(
            'todaySales',
            'todayOrders',
            'topMenu',
            'criticalStock',
            'recentOrders',
            'salesChart',
            'chartDays',
            'startDate',
            'endDate'
        ));
    }
}
