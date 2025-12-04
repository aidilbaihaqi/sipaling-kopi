<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function index(Request $request)
    {
        // Date range for chart
        $chartDays = $request->input('chart_days', 7);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($startDate && $endDate) {
            $chartStartDate = Carbon::parse($startDate)->startOfDay();
            $chartEndDate = Carbon::parse($endDate)->endOfDay();
        } else {
            $chartStartDate = now()->subDays($chartDays)->startOfDay();
            $chartEndDate = now()->endOfDay();
        }

        // Today's sales
        $todaySales = Order::whereDate('created_at', today())
            ->where('status', 'completed')
            ->sum('total_amount');

        // Today's orders
        $todayOrders = Order::whereDate('created_at', today())->count();

        // Top selling menu
        $topMenu = DB::table('order_details')
            ->join('menus', 'order_details.menu_id', '=', 'menus.id')
            ->select('menus.name', DB::raw('SUM(order_details.quantity) as total_sold'))
            ->groupBy('menus.id', 'menus.name')
            ->orderByDesc('total_sold')
            ->first();

        // Critical stock (< 30)
        $criticalStock = Menu::where('stock', '<', 30)
            ->where('is_available', true)
            ->get();

        // Recent orders
        $recentOrders = Order::with('user', 'items.menu')
            ->latest()
            ->take(10)
            ->get();

        // Sales chart data
        $salesData = Order::whereIn('status', ['completed', 'ready', 'processing', 'pending'])
            ->whereBetween('created_at', [$chartStartDate, $chartEndDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $salesChart = collect();
        $currentDate = $chartStartDate->copy();

        while ($currentDate <= $chartEndDate) {
            $dateString = $currentDate->format('Y-m-d');
            $salesChart->push([
                'date' => $dateString,
                'total' => isset($salesData[$dateString]) ? $salesData[$dateString]->total : 0
            ]);
            $currentDate->addDay();
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'today_sales' => $todaySales,
                'today_orders' => $todayOrders,
                'top_menu' => $topMenu,
                'critical_stock' => $criticalStock,
                'recent_orders' => $recentOrders,
                'sales_chart' => $salesChart,
                'chart_days' => $chartDays
            ]
        ]);
    }
}
