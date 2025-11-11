<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total penjualan hari ini
        $todaySales = Order::whereDate('created_at', today())
            ->where('status', 'completed')
            ->sum('total_amount');

        // Total pesanan hari ini
        $todayOrders = Order::whereDate('created_at', today())->count();

        // Menu terlaris (berdasarkan quantity)
        $topMenu = DB::table('order_items')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->select('menus.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('menus.id', 'menus.name')
            ->orderByDesc('total_sold')
            ->first();

        // Stok kritis (stok < 30)
        $criticalStock = Menu::where('stock', '<', 30)
            ->where('is_available', true)
            ->get();

        // Pesanan terbaru
        $recentOrders = Order::with('user', 'items.menu')
            ->latest()
            ->take(10)
            ->get();

        // Data untuk grafik penjualan 7 hari terakhir
        $salesChart = Order::where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(7))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.dashboard', compact(
            'todaySales',
            'todayOrders',
            'topMenu',
            'criticalStock',
            'recentOrders',
            'salesChart'
        ));
    }
}
