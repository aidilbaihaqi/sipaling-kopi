<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Exports\ReportExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Get report data
     */
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $orders = Order::with(['user', 'details.menu'])
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereIn('status', ['completed', 'ready', 'processing', 'pending'])
            ->latest()
            ->get();

        $totalRevenue = $orders->sum('total_amount');
        $totalOrders = $orders->count();
        $averagePerTransaction = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        $paymentMethods = $orders->groupBy('payment_method')->map(function($group) {
            return [
                'count' => $group->count(),
                'total' => $group->sum('total_amount')
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'orders' => $orders,
                'total_revenue' => $totalRevenue,
                'total_orders' => $totalOrders,
                'average_per_transaction' => $averagePerTransaction,
                'payment_methods' => $paymentMethods,
                'start_date' => $startDate,
                'end_date' => $endDate
            ]
        ]);
    }

    /**
     * Export report to Excel
     */
    public function export(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $orders = Order::with(['user', 'details.menu'])
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereIn('status', ['completed', 'ready', 'processing', 'pending'])
            ->latest()
            ->get();

        $filename = 'Laporan_Penjualan_' . $startDate . '_to_' . $endDate . '.xlsx';

        return Excel::download(new ReportExport($orders, $startDate, $endDate), $filename);
    }
}
